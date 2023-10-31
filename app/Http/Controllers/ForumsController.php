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
		$forums = Forum::with('user', 'tags')->where('active', 1)->where('group_id', 0)->latest('updated_at')->get();

		$tags = [''=>''] + ForumTag::pluck('name', 'id')->all();

		$tags_slug = ForumTag::where('group_id', 0)->pluck('slug', 'id')->all();

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
		$tags = ForumTag::where('group_id', 0)->pluck('name', 'id');

		return view('forums.create', compact('tags'));
	}

	/**
	 * Store a specific forum theme
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

        return redirect('forum')->with('message', 'Az új témát sikeresen felvetted!');
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
	
		$tags = ForumTag::where('group_id', 0)->pluck('name', 'id');
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
        $forum = Forum::findOrFail($id);

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

        return redirect('forum')->with('message', 'A fórum témát sikeresen módosítottad!');
	}

    public function set_body() {
        $forums = Forum::get();
        foreach($forums as $f) {
            $body = $f->body;
            $body = preg_replace("/<\/?div[^>]*\>/i", "", $body);
            $body = preg_replace("/<\/?span[^>]*\>/i", "", $body);
            $f->body = $body;
            $f->save();
        }
    }

    public function set_shorted_text() {
        $forums = Forum::where('shorted_text',NULL)->get();
        foreach($forums as $f) {
            $text = preg_replace("/<img[^>]+\>/i", "",$f->body); //a képet kivesszük belőle
            if(strlen($text)>600) {
                $f->shorted_text = $this->get_shorted_text($text,500);
                $f->save();
            }
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
