<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Traits\TagTrait;
use App\Models\Group;
use App\Models\Forum;
use App\Models\ForumTag;
use App\Models\Comment;
use App\Models\Notice;
use App\Http\Requests\ForumRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GroupThemesController extends Controller
{
    use TagTrait;

    public function __construct() {
        $this->middleware('auth');
    }


    public function index($id)
    {
        $group = Group::findOrFail($id);

        //ha nem csoport tag akkor a csoport főoldalára irányít
        if(!$group->isMember()) {
            return  redirect('csoport/'.$group->id.'/'.$group->slug);
        }

        $forums = Forum::with('user', 'tags')->where('group_id', $group->id)->where('active', 1)->latest('updated_at')->get();

        $tags = [''=>''] + ForumTag::where('group_id', $id)->pluck('name', 'id')->all();

        $tags_slug = ForumTag::where('group_id', $id)->pluck('slug', 'id')->all();

        $page = 'conversation';
        $status='active';

        return view('groupthemes.index', compact('group','page','status','forums', 'tags', 'tags_slug'));
    }

    public function announcement($id)
    {
        $group = Group::findOrFail($id);

        //ha nem csoport tag akkor a csoport főoldalára irányít
        if(!$group->isMember()) {
            return  redirect('csoport/'.$group->id.'/'.$group->slug);
        }

        $forums = Forum::with('user', 'tags')->where('group_id', $group->id)->where('active', 1)->where('announcement', 1)->latest('updated_at')->get();

        $page = 'announcement';
        $status='active';

        return view('groupthemes.index', compact('group','page','status','forums'));
    }

    public function closedthemes($id)
    {
        $group = Group::findOrFail($id);

        //ha nem csoport tag akkor a csoport főoldalára irányít
        if(!$group->isMember()) {
            return  redirect('csoport/'.$group->id.'/'.$group->slug);
        }

        $forums = Forum::with('user', 'tags')->where('group_id', $group->id)->where('active', 0)->latest('updated_at')->get();

        $tags = [''=>''] + ForumTag::where('group_id', $id)->pluck('name', 'id')->all();

        $tags_slug = ForumTag::pluck('slug', 'id')->all();

        $page = 'conversation';
        $status='closed';

        return view('groupthemes.index', compact('group','page','status','forums', 'tags', 'tags_slug'));
    }


    public function show($group_id,$group_slug,$forum_id,$forum_slug)
    {
        $group = Group::findOrFail($group_id);

        $user = Auth::user();

        //ha nem csoport tag akkor a csoport főoldalára irányít
        if(!$group->isMember()) {
            return  redirect('csoport/'.$group->id.'/'.$group->slug);
        }

        $forum = Forum::findOrFail($forum_id);

        $comments = Comment::where('commentable_type', 'App\Models\Forum')->where('commentable_id', $forum_id)->orderBy('lev1_comment_id', 'ASC')->orderBy('created_at', 'ASC')->get();


        $notice = Notice::findBy($forum_id,$user->id,'Forum')->first();

        $askNotice = 0;
        if($notice) {
            //nézi, hogy kértem-e értesítést ennél a témánál, ha igen, kipipálja
            $askNotice = $notice->ask_notice;

            //nulláza a notice számlálóját és ugyanannyival a user-ét is
            $user_new_post = $user->new_post - $notice->new;
            $user_new_post = $user_new_post < 0 ? 0 : $user_new_post;
            $user->new_post=$user_new_post;
            $user->save();
            $notice->update(['new'=>0,'read_it'=>1]);
        }

        $users_read_it_r = array();
        $notices = Notice::where('notifiable_id', $forum_id)->where('type', 'Forum')->where('read_it', 1)->get();
        foreach($notices as $n) {
            if($n->user->id!=$forum->user->id) {
                $users_read_it_r[] = '<a href="'.url('profil').'/'.$n->user->id.'/'.$n->user->slug.'">'.$n->user->name.'</a>';
            }
        }
        $users_read_it = implode(", ",$users_read_it_r);
        $num_user_read_it = count($users_read_it_r);

        return view('groupthemes.show', compact('group','forum','comments','askNotice','users_read_it','num_user_read_it'));
    }


    /**
     * Create a group theme
     *
     * @return Response
     */
    public function create($group_id,$slug,$type) {

        $group = Group::findOrFail($group_id);

        $tags = ForumTag::where('group_id', $group_id)->pluck('name', 'id');

        $title = $type=="tema" ? "Új téma" : "Új közlemény";

        $announcement = $type == "kozlemeny" ? true : NULL;

        return view('groupthemes.create', compact('tags','group_id','group','title','announcement'));
    }

    /**
     * Store a specific group theme
     *
     * @return Response
     */
    public function store(ForumRequest $request)
    {
        $tag_list=$this->getTagList($request->input('tag_list'), 'App\Models\ForumTag',$request->get('group_id'));

        $body = $request->get('body');
        $body = preg_replace("/<\/?div[^>]*\>/i", "", $body);
        $body = preg_replace("/<\/?span[^>]*\>/i", "", $body);
        $text = preg_replace("/<img[^>]+\>/i", "",$body); //a képet kivesszük belőle
        $shorted_text = strlen($body)>600 ? $this->get_shorted_text($text,500) : null;

        $forum = Auth::user()->forums()->create([
            'title' => $request->get('title'),
            'shorted_text' => $shorted_text,
            'body' => $body,
            'slug' => Str::slug($request->get('title')),
            'group_id' => $request->get('group_id')
        ]);

        $forum->tags()->attach($tag_list);

        $group = Group::findOrFail($forum->group_id);

        $is_announcement = $group->isAdmin() && $request->get('announcement');
        if($is_announcement) {
            $forum->update(['announcement' => 1]);
        }

        //Téma felvételkor notices táblában a forum_id-val felvevődik az összes user_id, a comment_id = 0, a new=1 lesz.
        foreach($group->members as $user) {
            $user_id = $user->id;

            $new = 0; //aki felvette a témát annak ez nem számít újnak
            if($user_id!=Auth::user()->id) {
                $user->new_post++; //a user-nél növeli az újak számlálóját
                $user->save();
                $new = 1;
            }

            $notice = Notice::create(['notifiable_id' => $forum->id,'user_id' =>$user_id,'type' => 'Forum','comment_id'=>0,'new'=>$new,'email' => 0,'email_sent' =>0,'ask_notice' => 0]);

            //ha új témára értesítést kér vagy közlemény, akkor beállítódik az email kiküldés (kivéve a létrehozót)
            if($user_id!=Auth::user()->id && (in_array($user_id, $group->member_list_with_new_post_notice) || $is_announcement)) {
                $notice->update(['email' => 1,'login_code' => Str::random(10)]);
            }
        }

        if($is_announcement) {
            return redirect('csoport/'.$forum->group_id.'/'.$group->slug.'/kozlemenyek')->with('message', 'A csoport közleményt sikeresen felvetted!');
        }
        else {
            return redirect('csoport/'.$forum->group_id.'/'.$group->slug.'/beszelgetesek')->with('message', 'A csoport beszélgetés témát sikeresen felvetted!');
        }
    }


    /**
     * Edit a specific group theme
     *
     * @param  integer $id The forum ID
     * @return Response
     */
    public function edit($group_id,$slug,$forum_id)
    {
        $group = Group::findOrFail($group_id);

        $forum = Forum::findOrFail($forum_id);

        if(Auth::user()->id != $forum->user->id) {
            return redirect('/');
        }

        $tags = ForumTag::where('group_id', $group_id)->pluck('name', 'id');
        $selected_tags = $forum->tags->pluck('id')->toArray();

        return view('groupthemes.edit', compact('forum', 'tags', 'selected_tags','group'));
    }

    /**
    * Update a specific group theme
    *
    * @param  integer $id The forum ID
    * @return Response
    */
    public function update($group_id,$group_slug,$forum_id,$forum_slug,ForumRequest $request)
    {
        $forum = Forum::findOrFail($forum_id);

        $tag_list=$this->getTagList($request->input('tag_list'), 'App\Models\ForumTag',$forum->group_id);

        $body = $request->get('body');
        $body = preg_replace("/<\/?div[^>]*\>/i", "", $body);
        $body = preg_replace("/<\/?span[^>]*\>/i", "", $body);
        $text = preg_replace("/<img[^>]+\>/i", "",$body); //a képet kivesszük belőle
        $shorted_text = strlen($text)>600 ? $this->get_shorted_text($text,500) : null;

        $forum->update([
            'title' => $request->get('title'),
            'shorted_text' => $shorted_text,
            'body' => $body,
            'slug' => Str::slug($request->get('title'))
        ]);

        $forum->tags()->sync($tag_list);

        $group = Group::findOrFail($forum->group_id);

        if($forum->announcement) {
            return redirect('csoport/'.$forum->group_id.'/'.$group->slug.'/kozlemenyek')->with('message', 'A csoport közleményt sikeresen módosítottad!');
        }
        else {
            return redirect('csoport/'.$forum->group_id.'/'.$group->slug.'/beszelgetesek')->with('message', 'A csoport beszélgetés témát sikeresen módosítottad!');
        }
    }

    /**
     * Close a group theme
     *
     * @return Response
     */
    public function closetheme($group_id,$group_slug,$forum_id,$forum_slug) {

        $group = Group::findOrFail($group_id);

        //ha nem csoport tag akkor a csoport főoldalára irányít
        if(!$group->isAdmin()) {
            return  redirect('csoport/'.$group->id.'/'.$group->slug)->with('message', 'Nincs jogosultságod a beszédet lezárni!');
        }

        $forum = Forum::findOrFail($forum_id);
        $forum->timestamps = false; //hogy az update_at ne módosuljon
        $forum->update(['active' => 0]);

        return redirect('csoport/'.$group_id.'/'.$group_slug.'/beszelgetesek')->with('message', 'A beszélgetést sikeresen lezártad!');
    }

    /**
     * Close a group theme
     *
     * @return Response
     */
    public function opentheme($group_id,$group_slug,$forum_id,$forum_slug) {

        $group = Group::findOrFail($group_id);

        //ha nem csoport tag akkor a csoport főoldalára irányít
        if(!$group->isAdmin()) {
            return  redirect('csoport/'.$group->id.'/'.$group->slug)->with('message', 'Nincs jogosultságod a beszédet lezárni!');
        }

        $forum = Forum::findOrFail($forum_id);
        $forum->timestamps = false; //hogy az update_at ne módosuljon
        $forum->update(['active' => 1]);

        return redirect('csoport/'.$group_id.'/'.$group_slug.'/beszelgetesek')->with('message', 'A beszélgetést sikeresen lezártad!');
    }
}
