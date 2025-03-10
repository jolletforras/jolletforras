<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\TagTrait;
use App\Http\Controllers\Traits\ZipCodeTrait;
use App\Models\Group;
use App\Models\GroupTag;
use App\Models\ProjectTag;
use App\Models\User;
use App\Models\Event;
use App\Models\Notice;
use App\Models\News;
use App\Models\Sendemail;
use App\Http\Requests\GroupRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mail;
use Illuminate\Support\Str;


class GroupsController extends Controller
{
    use TagTrait;
    use ZipCodeTrait;

    public function __construct() {
        $this->middleware('auth', ['except' => ['index','show','members']]);
    }

    /**
     * Displays all groups
     *
     *
     * @return Response
     */
    public function index()
    {

        $groups = Group::with('user', 'members', 'tags')->where('status','active')->orderBy('last_news_at','DESC');
        if(Auth::check())
        {
            $groups = $groups->get();
        }
        else {
            $groups = $groups->where('public','=', 1)->get();
        }

        $group_tags = GroupTag::getUsed();

        $tags = [''=>'']+$group_tags->pluck('name', 'id')->all();

        $city=NULL;

        $tags_slug = GroupTag::pluck('slug', 'id')->all();

        return view('groups.index', compact('groups', 'tags', 'tags_slug', 'city'));
    }

    /**
     * Displays a specific group
     *
     * @param  integer $id The group ID
     * @return Response
     */
    public function show($id)
    {
        $group = Group::findOrFail($id);

        //dd($group->admin_list);

        $page = 'description';

        if(Auth::check()) {
            $is_member = $group->isMember();

            $is_admin = in_array(Auth::user()->id, $group->admin_list);

            $members = $group->members()->orderBy('name', 'ASC')->pluck('name', 'user_id');
            //dd($members);

            $admins = $group->admins()->orderBy('name', 'ASC')->pluck('user_id')->toArray();
            $noadmins = $group->noadmins()->orderBy('name', 'ASC')->pluck('name', 'user_id');

            $nogroupmembers = $group->no_group_members_list;

            return view('groups.show', compact('group', 'page', 'members', 'nogroupmembers', 'admins', 'noadmins', 'is_member', 'is_admin'));
        }
        else {
            if(!$group->public) {                    //belépés oldalra irányít, amennyiben nincs bejelentkezve és nem nyilvános csoportot akar megnyitni
                return redirect('/login');
            }

            return view('groups.show_public', compact('group', 'page'));
        }
    }

    /**
     * Create a group
     *
     * @return Response
     */
    public function create() {
        $tags = GroupTag::pluck('name', 'id');

        $members = User::members()->orderBy('name', 'ASC')->pluck('name','id');

        $user_visibility = ['group'=>'csoport','portal'=>'az oldalon','public'=>'nyilvános'];

        return view('groups.create', compact('members','tags','user_visibility'));
    }

    /**
     * Store a specific group
     *
     * @return Response
     */
    public function store(GroupRequest $request)
    {
        $tag_list=$this->getTagList($request->input('tag_list'), 'App\Models\GroupTag');

        $description = $request->get('description');
        $agreement = $request->get('agreement');
        $member_info = $request->get('member_info');
        $admin_info = $request->get('admin_info');

        $zip_code=$request->get('zip_code');
        $coordinates=$this->getCoordinates($zip_code);

        $group = Auth::user()->groups()->create([
            'name' => $request->get('name'),
            'meta_description' => $request->get('meta_description'),
            'description' => $description,
            'agreement' => $agreement,
            'member_info' => $member_info,
            'admin_info' => $admin_info,
            'ask_motivation' => $request->has('ask_motivation') ? 1 : 0,
            'webpage_name' => $request->get('webpage_name'),
            'webpage_url' => addhttp($request->get('webpage_url')),
            'location' => $request->get('location'),
            'zip_code' => $zip_code,
            'lat' => $coordinates['lat'] ?? NULL,
            'lng' => $coordinates['lng'] ?? NULL,
            'city' => $request->get('city'),
            'slug' => Str::slug($request->get('name')),
            'public' => $request->has('public') ? 1 : 0,
            'user_visibility' => $request->get('user_visibility'),
            'last_news_at' => date('Y-m-d'),
            'counter' => 0
        ]);

        $group->tags()->attach($tag_list);

        $group->members()->attach(Auth::user()->id, ['admin'=>1]);

        return redirect('csoport/'.$group->id.'/'.$group->slug)->with('message', 'A csoportot sikeresen felvetted!');
    }

    /**
     * Edit a specific group
     *
     * @param  integer $id The group ID
     * @return Response
     */
    public function edit($id)
    {
        $group = Group::findOrFail($id);

        if(!in_array(Auth::user()->id,$group->admin_list)) {
            return redirect('/');
        }

        $members = User::members()->orderBy('name', 'ASC')->pluck('name','id');

        $tags = GroupTag::pluck('name', 'id');
        $selected_tags = $group->tags->pluck('id')->toArray();

        $user_visibility = ['group'=>'csoport','portal'=>'az oldalon','public'=>'nyilvános'];

        //dd($group);

        return view('groups.edit', compact('group', 'members', 'tags', 'selected_tags', 'user_visibility'));
    }

    /**
     * Update a specific group
     *
     * @param  integer $id The group ID
     * @return Response
     */
    public function update($id, GroupRequest $request)
    {
        $tag_list=$this->getTagList($request->input('tag_list'), 'App\Models\GroupTag');

        $group = Group::findOrFail($id);

        $zip_code=$request->get('zip_code');
        $coordinates=$this->getCoordinates($zip_code);

        $group->update([
            'name' => $request->get('name'),
            'meta_description' => $request->get('meta_description'),
            'description' => $request->get('description'),
            'agreement' => $request->get('agreement'),
            'member_info' => $request->get('member_info'),
            'admin_info' => $request->get('admin_info'),
            'ask_motivation' => $request->has('ask_motivation') ? 1 : 0,
            'webpage_name' => $request->get('webpage_name'),
            'webpage_url' => addhttp($request->get('webpage_url')),
            'location' => $request->get('location'),
            'zip_code' => $zip_code,
            'lat' => $coordinates['lat'] ?? NULL,
            'lng' => $coordinates['lng'] ?? NULL,
            'city' => $request->get('city'),
            'slug' => Str::slug($request->get('name')),
            'public' => $request->has('public') ? 1 : 0,
            'user_visibility' => $request->get('user_visibility'),
            'status' => $request->has('inactive') ? 'inactive' : 'active'
        ]);

        if($group->knowledge_tab) {
            $group->update(['knowledge_info' => $request->get('knowledge_info') ]);
        }

        $group->tags()->sync($tag_list);

        return redirect('csoport/'.$id.'/'.$group->slug)->with('message', 'A csoport leírását sikeresen módosítottad!');
    }


    /**
     * User join to a group
     *
     * @param  integer $id The group ID
     * @return Response
     */
    public function join($id, $name, Request $request)
    {
        $group = Group::findOrFail($id);

        $is_member = $group->isMember();

        if(!$is_member) {
            $user_id = Auth::user()->id;

            $group->members()->attach($user_id);
            if($group->ask_motivation) {
                $motivation = $request->get('motivation');
                DB::table('group_user')->where('group_id',$id)->where('user_id',$user_id)->update(['motivation' => $motivation]);

            }

            //itt abban az esetben ha adott témánál még nem létezik, akkor fel kell venni
            $themes = $group->themes()->pluck('id')->toArray();
            foreach($themes as $forum_id) {
                $notice = Notice::findBy($forum_id,$user_id,'Forum')->first();
                if(is_null($notice)) {
                    Notice::create(['group_id' => $id,'notifiable_id' => $forum_id,'user_id' =>$user_id,'type' => 'Forum','comment_id'=>0,'email' => 0,'email_sent' =>0,'ask_notice' => 0]);
                }
            }

            //itt abban az esetben ha adott eseménynél még nem létezik, akkor fel kell venni
            $events = $group->events()->pluck('id')->toArray();
            foreach($events as $event_id) {
                $notice = Notice::findBy($event_id,$user_id,'Event')->first();
                if(is_null($notice)) {
                    Notice::create(['group_id' => $id,'notifiable_id' => $event_id,'user_id' =>$user_id,'type' => 'Event','comment_id'=>0,'email' => 0,'email_sent' =>0,'ask_notice' => 0]);
                }
            }

            Notice::setNullUpdatedAt($group->id,$user_id);

            //értesítés az kezelőknek
            $admins = $group->admins()->get();

            $data['user_url'] = 'profil/' . $user_id . '/' . Auth::user()->slug;
            $data['user_name'] = Auth::user()->name;

            $data['group_url'] = 'csoport/' . $group->id . '/' . $group->slug;
            $data['group_name'] = $group->name;
            $data['subject'] = $group->name." - új tag";

            foreach($admins as $admin) {
                $data['email'] = $admin->email;
                $data['admin_name'] = $admin->name;

                $body = view('groups.emails.new_member_email',$data)->render();

                Sendemail::create([
                    'to_email' => $data['email'],
                    'subject' => $data['subject'],
                    'body' => $body
                ]);
            }
        }

        return redirect('csoport/'.$id.'/'.$name.'/beszelgetesek')->with('message', 'A csoporthoz sikeresen csatlakoztál!');
    }

    //az éles adatbázisban a hiányzó notice-ok felvétele
    public function add_notice()
    {
        $groups = Group::get();

        foreach ($groups as $group) {
            foreach ($group->members as $user) {
                $themes = $group->themes()->pluck('id')->toArray();
                foreach($themes as $forum_id) {
                    $notice = Notice::findBy($forum_id,$user->id,'Forum')->first();
                    if(is_null($notice)) {
                        Notice::create(['group_id'=> $group->id,'notifiable_id' => $forum_id,'user_id' =>$user->id,'type' => 'Forum','comment_id'=>0,'email' => 0,'email_sent' =>0,'ask_notice' => 0]);
                    }
                }
            }
        }
        echo 'Sikeresen fel lettek véve a notice-ok!';
    }

    //éles adatbázisban felveszi a group_id-kat a notices táblába
    public function add_group_id_to_notice()
    {
        $notices = Notice::get();

        foreach ($notices as $notice) {
            $class = "\\App\Models\\".$notice->type;
            $post = $class::findOrFail($notice->notifiable_id);

            $notice->timestamps = false;
            $notice->update(['group_id'=> $post->group_id]);

            //echo $i.'. '.$post->group_id.'<br>';
        }
        echo 'Sikeresen fel lettek véve a group_id-k a notices táblába!';
    }


    /**
     * User leaves the a group
     *
     * @param  integer $id The group ID
     * @return Response
     */
    public function leave($id, $name)
    {
        $group = Group::findOrFail($id);


        $is_member = $group->isMember();

        if($is_member) {
            $user_id = Auth::user()->id;

            $group->members()->detach($user_id);

            //törölni kell az adott csoport bejegyzéseire a user-t notice-ból, hogy csoport harangocska értesítőben már ne jelenjen meg
            Notice::where('user_id',$user_id)->where('group_id',$id)->delete();
            User::adjustGroupNewPost($user_id);

            /*
            //itt minden témánál az email-t 0-ra kell állítani, hogy már ne kapjon levelet
            $themes = $group->themes()->pluck('id')->toArray();
            foreach($themes as $forum_id) {
                $notice = Notice::findBy($forum_id,$user_id,'Forum')->first();
                $notice->timestamps = false; //hogy az update_at ne módosuljon
                $notice->update(['email' => 0]);
            }*/
        }

        return redirect('csoport/'.$id.'/'.$name)->with('message', 'A csoportból sikeresen kiléptél!');
    }

    public function members($id,$slug)
    {
        $group = Group::findOrFail($id);
        $isMember = $group->isMember();


        //ha nem regisztrált, de nem nyilvánosak a tagok, vagy ha regisztált akkor nem látható Jóllét Forrás szinten a tagok és nem csoport tag
        if(!(Auth::guest() && $group->user_visibility=='public' || Auth::check() && $group->user_visibility!='group' || $isMember)) {
            return  redirect('csoport/'.$group->id.'/'.$group->slug);
        }

        $users = $group->members();
        if(Auth::guest())
            $users = $users->where('public', 1);
        elseif($isMember && $group->ask_motivation)
            $users = $users->orderBy('pivot_updated_at', 'desc');
        $users = $users->get();

        $my_profile = null;
        if(Auth::check()) {
            $my_profile = $group->members()->where('user_id',Auth::user()->id)->first();
        }

        $page = 'members';

        return view('groups._members', compact('group','page','users','my_profile'));
    }


    public function events($id,$slug)
    {
        $group = Group::findOrFail($id);

        //ha nem csoport tag akkor a csoport főoldalára irányít
        if(!$group->isMember()) {
            return  redirect('csoport/'.$group->id.'/'.$group->slug);
        }

        $events = Event::latest()->where('group_id', $group->id)->where('expiration_date','>=',date('Y-m-d'))->get();
        $events_expired = Event::latest()->where('group_id', $group->id)->where('expiration_date','<',date('Y-m-d'))->get();

        $page = 'event';

        return view('groupevents.index', compact('group','page','events','events_expired'));
    }


    /**
     * Create a group theme
     *
     * @return Response
     */
    public function eventcreate($id,$group_slug) {

        $group = Group::findOrFail($id);

        $visibility = ['group'=>'csoport','portal'=>'az oldalon','public'=>'nyilvános'];

        return view('events.create', compact('visibility','group'));
    }

    public function saveAdmin($id, Request $request)
    {

        if(empty($request->input('admin_list'))) {
            $response = array('status' => 'error','message' => 'A kezelő nem törölhető, mivel egy kezelője kell legyen a csoportnak.');
        }
        else {
            DB::table('group_user')->where('group_id',$id)->update(['admin' => 0]);
            DB::table('group_user')->where('group_id',$id)->whereIn('user_id', $request->input('admin_list'))->update(['admin' => 1]);

            $response = array('status' => 'success');
        }

        return \Response::json($response);
    }

    public function removeMember($id, Request $request)
    {
        $group = Group::findOrFail($id);

        $group->members()->detach($request->input('remove_member'));

        $response = array(
            'status' => 'success',
        );

        return \Response::json($response);
    }

    public function invite($id, Request $request)
    {

        $user_id = $request->input('invited_user');

        $response = array(
            'status' => 'success',
            'msg' => $user_id
        );

        if($user_id!=0) {
            $group = Group::findOrFail($id);

            $invitantUser=Auth::user();

            $invitedUser = User::findOrFail($user_id);

            $data['invitant_id']=$invitantUser->id;
            $data['invitant_name']=$invitantUser->name;
            $data['group_id']=$group->id;
            $data['group_name']=$group->name;
            $data['group_slug']=$group->slug;
            $data['invited_name']=$invitedUser->name;
            $data['email']=$invitedUser->email;

            $body = view('groups.emails.invite_email',$data)->render();

            Sendemail::create([
                'to_email' => $data['email'],
                'subject' => "Meghívás a(z) ".$data['group_name']." csoportba",
                'body' => $body
            ]);

        }

        return \Response::json($response);
    }

    public function filter(Request $request)
    {
        $city=$request->get('city');

        $query = Group::with('user', 'members', 'tags')->where('status','active')->latest('updated_at');

        if (isset($city) && $city!="") {
            $query=$query->where('city','=', $city);
        }

        $groups=$query->get();

        $returnHTML = view('groups._card_list', compact('groups'))->render();

        //dd($returnHTML);

        $response = array(
            'status' => 'success',
            'html' => $returnHTML,
        );
        return \Response::json($response);
    }

    /**
     * Upload user group image
     *
     * @return Response
     */
    public function uploadImage($id,$name)
    {
        return view('groups.upload_image', compact('id','name'));
    }


    /**
     * Save user group image
     *
     * @return Response
     */
    public function saveImage($id,$name,Request $request)
    {
        $rules = [
            'image' => 'required|mimes:jpeg,png,gif|max:3072'
        ];

        $messages = [
            'image.required' => 'Képfájl kiválasztása szükséges',
            'image.mimes' => 'A kép fájltípusa .jpg, .png, .gif kell legyen',
            'image.max' => 'A kép nem lehet nagyobb mint :max KB',
        ];

        //dd($request);
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect('csoport/'.$id.'/'.$name.'/kepfeltoltes')
                ->withErrors($validator)
                ->withInput();
        }

        $imagename=$id;
        $base_path=base_path().'/public/images/groups/';
        $tmpimagename = 'tmp_'.$imagename.'.'.$request->file('image')->getClientOriginalExtension();
        $request->file('image')->move($base_path,$tmpimagename);

        $tmpfile=$base_path.$tmpimagename;
        generateImage($tmpfile, 400, 1, $base_path.$imagename.'.jpg');//1=>width
        unlink($tmpfile);

        $group = Group::findOrFail($id);
        $group->photo_counter++;
        $group->save();

        return redirect('/csoport/'.$id.'/'.$name)->with('message', 'A csoportképet sikeresen feltöltötted!');
    }

    public function motivation_update(Request $request)
    {
        $group_id = $request->get('group_id');
        $motivation = $request->get('motivation');

        if(!empty($motivation)) {
            DB::table('group_user')->where('group_id',$group_id)->where('user_id',Auth::user()->id)->update(['motivation' => $motivation, 'updated_at' => date("Y-m-d H:i:s")]);
        }

        $response = array('status' => 'success');

        return \Response::json($response);
    }

    public function cron_test()
    {
        //tesztelés esetén ide másold a SendNoticeEmails.php-ból
    }

}
