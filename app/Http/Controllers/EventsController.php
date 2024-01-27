<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Event;
use App\Models\Group;
use App\Models\Comment;
use App\Models\User;
use App\Models\Notice;
use App\Models\Sendemail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Mail;

class EventsController extends Controller
{
	public function __construct() {
		$this->middleware('auth', ['except'=>['index','show','events_expired']]);
	}

	public function index()
	{
        $events_expired = null;

        if(Auth::check())
        {
            $events = Event::where('expiration_date','>=',date('Y-m-d'))->where('visibility','<>', 'group')->orderBy('time', 'ASC')->get();
        }
        else {
            $events = Event::where('expiration_date','>=',date('Y-m-d'))->where('visibility','=', 'public')->orderBy('time', 'ASC')->get();
        }

		return view('events.index', compact('events'));
	}


    public function events_expired()
    {
        $events = null;

        if(Auth::check())
        {
            $events = Event::latest()->where('expiration_date','<',date('Y-m-d'))->where('visibility','<>', 'group')->get();
        }
        else {
            $events = Event::latest()->where('expiration_date','<',date('Y-m-d'))->where('visibility','=', 'public')->get();
        }

        $content_html ='<h2>Lejárt események</h2><br>';

        $content_html .= view('events._list', compact('events'))->render();

        $response = array(
            'status' => 'success',
            'content_html' => $content_html,
        );

        return \Response::json($response);
    }


    /**
     * Displays a specific event
     *
     * @param  integer $id The event ID
     * @return Response
     */
    public function show($id)
    {
        $event = Event::findOrFail($id);

        //ha nincs bejelentkezve és az esemény nem nyilvános
        if(!Auth::check() && $event->visibility != 'public') {
            return redirect('/login');
        }

        $users_read_it = '';

        if($event->isGroupEvent()) {
            $group = Group::findOrFail($event->group_id);

            //ha a láthatóság "csoport" és nem csoport tag akkor a csoport főoldalára irányít
            if($event->visibility == 'group' && !$group->isMember()) {
                return  redirect('csoport/'.$group->id.'/'.$group->slug);
            }

            //esemény megnézése után nulláza a notice számlálóját és ugyanannyival a user-ét is, amenniben van
            if(Auth::check()) {
                $notice = Notice::findBy($id,Auth::user()->id,'Event')->first();
                if($notice) {
                    $user_new_post = Auth::user()->new_post - $notice->new;
                    $user_new_post = $user_new_post < 0 ? 0 : $user_new_post;
                    Auth::user()->update(['new_post'=>$user_new_post]);
                    $notice->update(['new'=>0,'read_it'=>1]);
                }
            }

            $has_access = $group->isAdmin();

            $users_read_it_r = array();
            $notices = Notice::where('notifiable_id', $id)->where('type', 'Event')->where('read_it', 1)->get();
            foreach($notices as $n) {
                if($n->user->id!=$event->user->id) {
                    $users_read_it_r[] = '<a href="'.url('profil').'/'.$n->user->id.'/'.$n->user->slug.'">'.$n->user->name.'</a>';
                }
            }
            $users_read_it = implode(", ",$users_read_it_r);
        }
        else {
            $has_access = $event->isEditor();
        }

        $comments = Comment::where('commentable_type', 'App\Models\Event')->where('commentable_id', $id)->orderBy('lev1_comment_id', 'ASC')->orderBy('created_at', 'ASC')->get();

        $participate = false;
        $participants = '';
        $participants_with_me = '';
        if(Auth::check()) {
            $participants_r = array();
            foreach($event->participants as $user) {
                if($user->id==Auth::user()->id) {
                    $participate = true;
                }
                else {
                    $participants_r[] = '<a href="'.url('profil').'/'.$user->id.'/'.$user->slug.'">'.$user->name.'</a>';
                }
            }
            $participants = implode(", ",$participants_r);
            $participants_r[] = '<a href="'.url('profil').'/'.Auth::user()->id.'/'.Auth::user()->slug.'">'.Auth::user()->name.'</a>';
            $participants_with_me = implode(", ",$participants_r);
        }

        return view('events.show', compact('event','has_access', 'comments', 'users_read_it','participate','participants','participants_with_me'));
    }



	/**
	 * Create a specific event
	 *
	 * @return Response
	 */
	public function create()
	{
        $visibility = ['portal'=>'portál','public'=>'nyilvános'];

		return view('events.create', compact('visibility'));
	}


	/**
	 * Store a specific event
	 *
	 * @param  Request $request
	 * @return Response
	 */
	public function store(Request $request) {

        $body = $request->get('body');
        $body = preg_replace("/<\/?div[^>]*\>/i", "", $body);
        $body = preg_replace("/<\/?span[^>]*\>/i", "", $body);
        $text = preg_replace("/<img[^>]+\>/i", "",$body); //a képet kivesszük belőle
        $shorted_text = strlen($body)>600 ? $this->get_shorted_text($text,500) : null;

        $event = Auth::user()->events()->create([
            'title' => $request->get('title'),
            'meta_description' => $request->get('meta_description'),
            'time' => $request->get('time'),
            'expiration_date' => $request->get('expiration_date'),
            'shorted_text' => $shorted_text,
            'body' => $body,
            'image' => getfirstimage($body),
            'slug' => Str::slug($request->get('title')),
            'visibility' => $request->get('visibility'),
            'group_id' => $request->get('group_id')
        ]);

        if($event->group_id==0) {
            return redirect('esemenyek')->with('message', 'Az új eseményt sikeresen felvetted!');
        }
        else {
            $group = Group::findOrFail($event->group_id);

            //Esemény felvételkor notices táblában az event_id-val felvevődik az összes user_id, a comment_id = 0, a new=1 lesz.
            foreach($group->members as $user) {
                $user_id = $user->id;

                $new = 0; //aki felvette a témát annak ez nem számít újnak
                if ($user_id != Auth::user()->id) {
                    $user->new_post++; //a user-nél növeli az újak számlálóját
                    $user->save();
                    $new = 1;
                }

                $notice = Notice::create(['group_id' => $event->group_id,'notifiable_id' => $event->id,'user_id' =>$user_id,'type' => 'Event','comment_id'=>0,'new'=>$new,'email' => 0,'email_sent' =>0,'ask_notice' => 0]);

                //beállítódik az email kiküldés minden csoport tagra(kivéve a létrehozót)
                if ($user_id != Auth::user()->id) {
                    $notice->update(['email' => 1, 'login_code' => Str::random(10)]);
                }
            }

            return redirect('csoport/'.$event->group_id.'/'.$group->slug.'/esemenyek')->with('message', 'A csoport eseményt sikeresen felvetted!');
        }
	}

	/**
	 * Edit a specific event
	 *
	 * @param  integer $id The forum ID
	 * @return Response
	 */
	public function edit($id)
	{
		$event = Event::findOrFail($id);

		if($event->group_id==0) {
            if(!($event->isEditor() || Auth::user()->admin)) return redirect('/');

		    $visibility = ['portal'=>'portál','public'=>'nyilvános'];
        } else {
            $group = Group::findOrFail($event->group_id);

            if (!($event->isEditor() || $group->isAdmin())) return redirect('/');

            $visibility = ['group'=>'csoport','portal'=>'portál','public'=>'nyilvános'];
        }

		return view('events.edit', compact('event','visibility'));
	}

	/**
	 * Update a specific event
	 *
	 * @param  integer $id, Request $request
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$event = Event::findOrFail($id);

        $body = $request->get('body');
        $body = preg_replace("/<\/?div[^>]*\>/i", "", $body);
        $body = preg_replace("/<\/?span[^>]*\>/i", "", $body);
        $text = preg_replace("/<img[^>]+\>/i", "",$body); //a képet kivesszük belőle
        $shorted_text = strlen($text)>600 ? $this->get_shorted_text($text,500) : null;

        $event->update([
            'title' => $request->get('title'),
            'meta_description' => $request->get('meta_description'),
            'time' => $request->get('time'),
            'expiration_date' => $request->get('expiration_date'),
            'shorted_text' => $shorted_text,
            'body' => $body,
            'image' => getfirstimage($body),
            'slug' => Str::slug($request->get('title')),
            'visibility' => $request->get('visibility'),
            'group_id' => $request->get('group_id')
        ]);

        if($event->group_id==0) {
            return redirect('esemenyek')->with('message', 'Az eseményt sikeresen módosítottad!');
        }
        else {
            $group = Group::findOrFail($event->group_id);

            return redirect('csoport/'.$event->group_id.'/'.$group->slug.'/esemenyek')->with('message', 'A csoport eseményt sikeresen módosítottad!');
        }
	}

    /**
     * Invite a no group member to a group public event
     *
     * @return Response
     */
    public function invite($id, Request $request)
    {

        $user_id = $request->input('invited_user');

        $response = array(
            'status' => 'success',
            'msg' => $user_id
        );

        if($user_id!=0) {
            $event = Event::findOrFail($id);

            $group = $event->group;

            $invitantUser=Auth::user();

            $invitedUser = User::findOrFail($user_id);

            $data['invitant_id']=$invitantUser->id;
            $data['invitant_name']=$invitantUser->name;

            $data['event_id']=$event->id;
            $data['event_name']=$event->title;
            $data['event_slug']=$event->slug;

            $data['group_id']=$group->id;
            $data['group_name']=$group->name;
            $data['group_slug']=$group->slug;
            $data['invited_name']=$invitedUser->name;
            $data['email']=$invitedUser->email;

            $body = view('events.invite_email',$data)->render();

            Sendemail::create([
                'to_email' => $data['email'],
                'subject' => "Meghívás a(z) ".$data['group_name']." - ".$data['event_name']." eseményre",
                'body' => $body
            ]);


        }

        return \Response::json($response);
    }

    /**
     * Set participation on this event
     *
     * @return Response
     */
    public function participate($id, Request $request)
    {
        $event = Event::findOrFail($id);
        $user_id = Auth::user()->id;
        $participate = $request->get('participate');

        if($participate) {
            $event->participants()->attach($user_id);
        }
        else {
            $event->participants()->detach($user_id);
        }

        $response = array('status' => 'success');

        return \Response::json($response);
    }

    public function set_body() {
        $events = Event::get();
        foreach($events as $e) {
            $body = $e->body;
            $body = preg_replace("/<\/?div[^>]*\>/i", "", $body);
            $body = preg_replace("/<\/?span[^>]*\>/i", "", $body);
            $e->body = $body;
            $e->save();
        }
    }

    public function set_shorted_text() {
        $events = Event::where('shorted_text',NULL)->get();
        foreach($events as $e) {
            $text = preg_replace("/<img[^>]+\>/i", "",$e->body); //a képet kivesszük belőle
            if(strlen($text)>600) {
                $e->shorted_text = $this->get_shorted_text($text,500);
                $e->save();
            }
        }
    }

    public function set_image() {
        $events = Event::where('image',NULL)->get();
        foreach($events as $e) {
            $e->image = getfirstimage($e->body);
            $e->save();
        }
    }

    private function get_shorted_text($text,$min_length)
    {
        $pos = mb_strpos($text,"</p>",500);
        if($pos>700) {
            $text = str_replace("</p>","<br>",$text);
            $text = str_replace("<br/>","<br>",$text);
            $text = str_replace("<br />","<br>",$text);
            $text = strip_tags($text,"<br>,<a>");
            $pos = mb_strpos($text,"<br>",500);
            $text = mb_substr($text,0,$pos)." #...#<br>";
        }
        else {
            $text = mb_substr($text,0,$pos)." #...#</p>";
        }
        return $text;
    }
}
