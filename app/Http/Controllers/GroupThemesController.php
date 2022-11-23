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

        //ha nem csoport tag akkor a csoport főoldalára irányít
        if(!$group->isMember()) {
            return  redirect('csoport/'.$group->id.'/'.$group->slug);
        }

        $forum = Forum::findOrFail($forum_id);

        $comments = Comment::where('commentable_type', 'App\Models\Forum')->where('commentable_id', $forum_id)->get();


        $notice = Notice::findBy($forum_id,Auth::user()->id,'Forum')->first();

        $askNotice = 0;
        if($notice) {
            //nézi, hogy kértem-e értesítést ennél a témánál, ha igen, kipipálja
            $askNotice = $notice->ask_notice;

            //nulláza a notice számlálóját és ugyanannyival a user-ét is
            $user_new_post = Auth::user()->new_post - $notice->new;
            $user_new_post = $user_new_post < 0 ? 0 : $user_new_post;
            Auth::user()->update(['new_post'=>$user_new_post]);
            $notice->update(['new'=>0]);
        }

        return view('groupthemes.show', compact('group','forum','comments','askNotice'));
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
