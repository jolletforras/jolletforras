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
use App\Models\User;
use App\Models\Forum;
use App\Models\ForumTag;
use App\Models\Comment;
use App\Models\Event;
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
        $this->middleware('auth', ['except' => ['index','show']]);
    }

    /**
     * Displays all groups
     *
     *
     * @return Response
     */
    public function index()
    {
        if(Auth::check())
        {
            $groups = Group::with('user', 'members', 'tags')->latest('updated_at')->get();
        }
        else {
            $groups = Group::with('user', 'members', 'tags')->latest('updated_at')->where('public','=', 1)->get();
        }

        $tags = [''=>''] + GroupTag::pluck('name', 'id')->all();

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

        if(Auth::check()) {

            $page = 'description';

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
            return view('groups.show_public', compact('group'));
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

        return view('groups.create', compact('members','tags'));
    }

    /**
     * Store a specific group
     *
     * @return Response
     */
    public function store(GroupRequest $request)
    {
        $tag_list=$this->getTagList($request->input('tag_list'), 'App\Models\GroupTag');

        //$url = '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i';
        $description = htmlspecialchars($request->get('description'));
        //$description = preg_replace($url, '<a href="$0" target="_blank" title="$0">$0</a>', $description);

        $agreement = htmlspecialchars($request->get('agreement'));
        //$agreement = preg_replace($url, '<a href="$0" target="_blank" title="$0">$0</a>', $agreement);

        $zip_code=$request->get('zip_code');
        $coordinates=$this->getCoordinates($zip_code);

        $group = Auth::user()->groups()->create([
            'name' => $request->get('name'),
            'description' => $description,
            'agreement' => $agreement,
            'webpage_name' => $request->get('webpage_name'),
            'webpage_url' => addhttp($request->get('webpage_url')),
            'location' => $request->get('location'),
            'zip_code' => $zip_code,
            'lat' => $coordinates['lat'],
            'lng' => $coordinates['lng'],
            'city' => $request->get('city'),
            'slug' => Str::slug($request->get('name')),
            'public' => $request->has('public') ? 1 : 0,
            'counter' => 0,
            'create_at' => date("Y-m-d H:i:s", strtotime('now'))
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

        return view('groups.edit', compact('group', 'members', 'tags', 'selected_tags'));
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

        //$url = '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i';
        $description = htmlspecialchars($request->get('description'));
        //$description = preg_replace($url, '<a href="$0" target="_blank" title="$0">$0</a>', $description);

        $agreement = htmlspecialchars($request->get('agreement'));
        //$agreement = preg_replace($url, '<a href="$0" target="_blank" title="$0">$0</a>', $agreement);

        $zip_code=$request->get('zip_code');
        $coordinates=$this->getCoordinates($zip_code);

        $group->update([
            'name' => $request->get('name'),
            'description' => $description,
            'agreement' => $agreement,
            'webpage_name' => $request->get('webpage_name'),
            'webpage_url' => addhttp($request->get('webpage_url')),
            'location' => $request->get('location'),
            'zip_code' => $zip_code,
            'lat' => $coordinates['lat'],
            'lng' => $coordinates['lng'],
            'city' => $request->get('city'),
            'slug' => Str::slug($request->get('name')),
            'public' => $request->has('public') ? 1 : 0,
            'updated_at' => date("Y-m-d H:i:s", strtotime('now'))
        ]);

        $group->tags()->sync($tag_list);

        return redirect('csoport/'.$id.'/'.$group->slug)->with('message', 'A csoport leírását sikeresen módosítottad!');
    }


    /**
     * User join to a group
     *
     * @param  integer $id The group ID
     * @return Response
     */
    public function join($id, $name)
    {
        $group = Group::findOrFail($id);


        $is_member = $group->isMember();

        if(!$is_member) {
            $group->members()->attach(Auth::user()->id);
        }

        return redirect('csoport/'.$id.'/'.$name.'/beszelgetesek')->with('message', 'A csoporthoz sikeresen csatlakoztál!');
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
            $group->members()->detach(Auth::user()->id);
        }

        return redirect('csoport/'.$id.'/'.$name)->with('message', 'A csoportból sikeresen kiléptél!');
    }


    public function conversations($id)
    {
        $group = Group::findOrFail($id);

        //ha nem csoport tag akkor a csoport főoldalára irányít
        if(!$group->isMember()) {
            return  redirect('csoport/'.$group->id.'/'.$group->slug);
        }

        $forums = Forum::with('user', 'tags')->where('group_id', $group->id)->latest('updated_at')->get();

        $tags = [''=>''] + ForumTag::pluck('name', 'id')->all();

        $tags_slug = ForumTag::pluck('slug', 'id')->all();

        $page = 'conversation';

        return view('groupthemes.index', compact('group','page','forums', 'tags', 'tags_slug'));
    }

    public function events($id,$slug)
    {
        $group = Group::findOrFail($id);

        //ha nem csoport tag akkor a csoport főoldalára irányít
        if(!$group->isMember()) {
            return  redirect('csoport/'.$group->id.'/'.$group->slug);
        }

        $events = Event::latest()->where('group_id', $group->id)->get();

        $page = 'event';

        return view('groupevents.index', compact('group','page','events'));
    }


    public function theme($group_id,$group_slug,$forum_id,$forum_slug)
    {
        $group = Group::findOrFail($group_id);

        //ha nem csoport tag akkor a csoport főoldalára irányít
        if(!$group->isMember()) {
            return  redirect('csoport/'.$group->id.'/'.$group->slug);
        }

        $forum = Forum::findOrFail($forum_id);

        $comments = Comment::where('commentable_type', 'App\Models\Forum')->where('commentable_id', $forum_id)->get();

        //new=0 hozzászolás értesítés
        $notice = DB::table('forum_user')->where('forum_id',$forum_id)->where('user_id',Auth::user()->id)->where('new',0)->where('ask_notice',1)->first();
        $askNotice = empty($notice) ? 0 : 1;

        return view('groupthemes.show', compact('group','forum','comments','askNotice'));
    }


    /**
     * Create a group theme
     *
     * @return Response
     */
    public function themecreate($group_id,$slug) {

        $tags = ForumTag::pluck('name', 'id');

        return view('groupthemes.create', compact('tags','group_id'));
    }

    /**
     * Create a group theme
     *
     * @return Response
     */
    public function eventcreate($id,$group_slug) {

        $group = Group::findOrFail($id);

        $visibility = ['group'=>'csoport','portal'=>'portál','public'=>'nyilvános'];

        return view('events.create', compact('visibility','group'));
    }

    public function saveAdmin($id, Request $request)
    {

        DB::table('group_user')->where('group_id',$id)->update(['admin' => 0]);
        DB::table('group_user')->where('group_id',$id)->whereIn('user_id', $request->input('admin_list'))->update(['admin' => 1]);

        $response = array(
            'status' => 'success',
        );

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

            Mail::send('groups.invite_email', $data, function($message) use ($data)
            {
                $message->from('tarsadalmi.jollet@gmail.com', "tarsadalmijollet.hu");
                $message->subject("Meghívás a(z) ".$data['group_name']." csoportba");
                $message->to($data['email']);
            });

        }

        return \Response::json($response);
    }

    public function filter(Request $request)
    {
        $city=$request->get('city');

        $query = Group::with('user', 'members', 'tags')->latest('updated_at');

        if (isset($city) && $city!="") {
            $query=$query->where('city','=', $city);
        }

        $groups=$query->get();

        $returnHTML = view('groups._group_list', compact('groups'))->render();

        //dd($returnHTML);

        $response = array(
            'status' => 'success',
            'html' => $returnHTML,
        );
        return \Response::json($response);
    }

}
