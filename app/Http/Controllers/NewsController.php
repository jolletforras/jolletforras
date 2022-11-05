<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\TagTrait;
use App\Models\News;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\NewsTag;

class NewsController extends Controller
{
    use TagTrait;

    public function __construct() {
		$this->middleware('auth', ['except'=>['index','show']]);
	}

	public function index(Request $request)
	{
		$newss = News::latest()->get();

        $tags = [''=>''] + NewsTag::pluck('name', 'id')->all();

        $tags_slug = NewsTag::pluck('slug', 'id')->all();

		return view('news.index', compact('newss', 'tags', 'tags_slug'));
	}

    /**
     * Displays a specific article
     *
     * @param  integer $id The article ID
     * @return Response
     */
    public function show($id)
    {
        $news = News::findOrFail($id);

        return view('news.show', compact('news'));
    }
	
	
	public function create(Request $request) 
	{

        $tags = NewsTag::pluck('name', 'id');

		return view('news.create', compact('tags'));
	}
	
	public function store(Request $request)
	{
        $tag_list=$this->getTagList($request->input('tag_list'), 'App\Models\NewsTag');

        $news = Auth::user()->news()->create([
            'title' => $request->get('title'),
            'body' => $request->get('body'),
            'slug' => Str::slug($request->get('title'))
        ]);

        $news->tags()->attach($tag_list);

		return redirect('hirek');
	}

	/**
	 * Edit a specific news
	 *
	 * @param  integer $id The forum ID
	 * @return Response
	 */
	public function edit($id, Request $request)
	{
	    $news = News::findOrFail($id);

		if(!(Auth::user()->id == $news->user_id || Auth::user()->admin)) {
			return redirect('/');
		}

        $tags = NewsTag::pluck('name', 'id');
        $selected_tags = $news->tags->pluck('id')->toArray();

		return view('news.edit', compact('news', 'tags', 'selected_tags'));
	}

	/**
	 * Update a specific news/event
	 *
	 * @param  integer $id The forum ID
	 * @return Response
	 */
	public function update($id, Request $request)
	{
        $tag_list=$this->getTagList($request->input('tag_list'), 'App\Models\NewsTag');

	    $news = News::findOrFail($id);

        //$nws->update($request->all());

        $news->update([
            'title' => $request->get('title'),
            'body' => $request->get('body'),
            'slug' => Str::slug($request->get('title'))
        ]);

        $news->tags()->sync($tag_list);

		return redirect('hirek');
	}
}
