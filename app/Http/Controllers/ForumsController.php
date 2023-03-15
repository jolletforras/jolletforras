<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\TagTrait;
use App\Models\Forum;
use App\Models\Group;
use App\Models\ForumTag;
use App\Models\Comment;
use App\Models\Notice;
use App\Http\Requests\ForumRequest;
use Illuminate\Support\Facades\Auth;
use DB;
use Mail;
use Illuminate\Support\Str;


class ForumsController extends Controller
{
	use TagTrait;
	
	public function __construct() {
		$this->middleware('auth');
		//$this->middleware('owner', ['only' => 'edit']);
	}

	/**
	 * Displays all forums
	 *
	 *
	 * @return Response
	 */
	public function index()
	{
		$forums = Forum::with('user', 'tags')->where('group_id', 0)->latest('updated_at')->get();

		$tags = [''=>''] + ForumTag::pluck('name', 'id')->all();

		$tags_slug = ForumTag::pluck('slug', 'id')->all();

		return view('forums.index', compact('forums', 'tags', 'tags_slug'));
	}
	
	/**
	 * Displays a specific forum theme
	 *
	 * @param  integer $id The forum ID
	 * @return Response
	 */
	public function show($id)
	{
		$forum = Forum::findOrFail($id);

        if($forum->group_id!=0) {
            return redirect('forum')->with('message', 'Nem létező fórum téma!');
        }

        $comments = Comment::where('commentable_type', 'App\Models\Forum')->where('commentable_id', $id)->get();

		return view('forums.show', compact('forum','comments'));
	}
	
	/**
	 * Create a forum theme
	 *
	 * @return Response
	 */
	public function create() {
		$tags = ForumTag::pluck('name', 'id');

		return view('forums.create', compact('tags'));
	}

	/**
	 * Store a specific forum theme
	 *
	 * @return Response
	 */
	public function store(ForumRequest $request)
	{
		$tag_list=$this->getTagList($request->input('tag_list'), 'App\Models\ForumTag');

		$forum = Auth::user()->forums()->create([
				'title' => $request->get('title'),
				'body' => $request->get('body'),
				'slug' => Str::slug($request->get('title')),
                'group_id' => $request->get('group_id')
		]);

		$forum->tags()->attach($tag_list);

        if($forum->group_id==0) {
            return redirect('forum')->with('message', 'Az új témát sikeresen felvetted!');
        }
        else {
            $group = Group::findOrFail($forum->group_id);

            if($group->isAdmin()) {
                $announcement = empty($request->get('announcement')) ? 0 : 1;
                $forum->update(['announcement' => $announcement]);
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

                //ha új témára értesítést kér, akkor beállítódik az email kiküldés (kivéve a létrehozót)
                if($user_id!=Auth::user()->id && in_array($user_id, $group->member_list_with_new_post_notice)) {
                    $notice->update(['email' => 1,'login_code' => Str::random(10)]);
                }
            }

            return redirect('csoport/'.$forum->group_id.'/'.$group->slug.'/beszelgetesek')->with('message', 'A csoport beszélgetés témát sikeresen felvetted!');
        }
	}
	
	/**
	 * Edit a specific forum
	 *
	 * @param  integer $id The forum ID
	 * @return Response
	 */
	public function edit($id)
	{
		$forum = Forum::findOrFail($id);
		
		if(Auth::user()->id != $forum->user->id) {
			return redirect('/');
		}
	
		$tags = ForumTag::pluck('name', 'id');
        $selected_tags = $forum->tags->pluck('id')->toArray();
		
		return view('forums.edit', compact('forum', 'tags', 'selected_tags'));
	}
	
	/**
	 * Update a specific forum
	 *
	 * @param  integer $id The forum ID
	 * @return Response
	 */
	public function update($id, ForumRequest $request)
	{
		$tag_list=$this->getTagList($request->input('tag_list'), 'App\Models\ForumTag');
		
		$forum = Forum::findOrFail($id);

		$forum->update([
				'title' => $request->get('title'),
				'body' => $request->get('body'),
				'slug' => Str::slug($request->get('title'))
		]);
	
		$forum->tags()->sync($tag_list);

        if($forum->group_id==0) {
            return redirect('forum')->with('message', 'A fórum témát sikeresen módosítottad!');
        }
        else {
            $group = Group::findOrFail($forum->group_id);

            if($group->isAdmin()) {
                $announcement = empty($request->get('announcement')) ? 0 : 1;
                $forum->update(['announcement' => $announcement]);
            }

            return redirect('csoport/'.$forum->group_id.'/'.$group->slug.'/beszelgetesek')->with('message', 'A csoport beszélgetés témát sikeresen módosítottad!');
        }
	}
}
