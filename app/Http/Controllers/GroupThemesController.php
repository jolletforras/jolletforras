<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Forum;
use App\Models\ForumTag;
use App\Models\Comment;
use App\Models\Notice;
use Illuminate\Support\Facades\Auth;

class GroupThemesController extends Controller
{

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

        $forums = Forum::with('user', 'tags')->where('group_id', $group->id)->latest('updated_at')->get();

        $tags = [''=>''] + ForumTag::pluck('name', 'id')->all();

        $tags_slug = ForumTag::pluck('slug', 'id')->all();

        $page = 'conversation';

        return view('groupthemes.index', compact('group','page','forums', 'tags', 'tags_slug'));
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

        $comments = Comment::where('commentable_type', 'App\Models\Forum')->where('commentable_id', $forum_id)->get();


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

        return view('groupthemes.show', compact('group','forum','comments','askNotice','users_read_it'));
    }


    /**
     * Create a group theme
     *
     * @return Response
     */
    public function create($group_id,$slug) {

        $tags = ForumTag::pluck('name', 'id');

        return view('groupthemes.create', compact('tags','group_id'));
    }
}
