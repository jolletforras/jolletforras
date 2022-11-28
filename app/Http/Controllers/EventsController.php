<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Event;
use App\Models\Group;
use App\Models\Comment;
use App\Models\User;
use App\Models\Notice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Mail;

class EventsController extends Controller
{
	public function __construct() {
		$this->middleware('auth', ['except'=>['index','show']]);
	}

	public function index()
	{
        if(Auth::check())
        {
            $events = Event::latest()->where('visibility','<>', 'group')->get();
        }
        else {
            $events = Event::latest()->where('visibility','=', 'public')->get();
        }

		return view('events.index', compact('events'));
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
            return redirect('/');
        }

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
                    $notice->update(['new'=>0]);
                }
            }

            $has_access = $group->isAdmin();
        }
        else {
            $has_access = $event->isEditor();
        }

        $comments = Comment::where('commentable_type', 'App\Models\Event')->where('commentable_id', $id)->get();

        return view('events.show', compact('event','has_access', 'comments'));
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

        $event = Auth::user()->events()->create([
            'title' => $request->get('title'),
            'meta_description' => $request->get('meta_description'),
            'body' => $request->get('body'),
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

                $notice = Notice::create(['notifiable_id' => $event->id,'user_id' =>$user_id,'type' => 'Event','comment_id'=>0,'new'=>$new,'email' => 0,'email_sent' =>0,'ask_notice' => 0]);

                //ha új eseményre értesítést kér, akkor beállítódik az email kiküldés (kivéve a létrehozót)
                if ($user_id != Auth::user()->id && in_array($user_id, $group->member_list_with_new_post_notice)) {
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
            if(!$event->isEditor()) return redirect('/');

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

        $event->update([
            'title' => $request->get('title'),
            'meta_description' => $request->get('meta_description'),
            'body' => $request->get('body'),
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

            Mail::send('events.invite_email', $data, function($message) use ($data)
            {
                $message->from('tarsadalmi.jollet@gmail.com', "tarsadalmijollet.hu");
                $message->subject("Meghívás a(z) ".$data['group_name']." - ".$data['event_name']." eseményre");
                $message->to($data['email']);
            });

        }

        return \Response::json($response);
    }
}
