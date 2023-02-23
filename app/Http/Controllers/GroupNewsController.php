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
use App\Models\Group;

class GroupNewsController extends Controller
{
    use TagTrait;

    public function __construct() {
		$this->middleware('auth', ['except'=>['index','show']]);
	}

	public function index($id)
	{
        $group = Group::findOrFail($id);

        //ha nem csoport tag akkor a csoport főoldalára irányít
        if(!$group->isMember()) {
            return  redirect('csoport/'.$group->id.'/'.$group->slug);
        }

		$newss = News::where('group_id', $group->id)->latest()->get();

        $tags = [''=>''] + NewsTag::pluck('name', 'id')->all();

        $tags_slug = NewsTag::pluck('slug', 'id')->all();

        $page = 'news';

		return view('groupnews.index', compact('group', 'page', 'newss', 'tags', 'tags_slug'));
	}

    /**
     * Displays a specific article
     *
     * @param  integer $id The article ID
     * @return Response
     */
    public function show($group_id,$group_slug,$news_id,$news_slug)
    {
        $news = News::findOrFail($news_id);

        return view('groupnews.show', compact('news'));
    }
	
	
	public function create($group_id)
	{

        $tags = NewsTag::pluck('name', 'id');

		return view('groupnews.create', compact('tags','group_id'));
	}
	
	public function store(Request $request)
	{

        $news = Auth::user()->news()->create([
            'title' => $request->get('title'),
            'meta_description' => $request->get('meta_description'),
            'body' => $request->get('body'),
            'slug' => Str::slug($request->get('title')),
            'group_id' => $request->get('group_id')
        ]);

        if(!empty($request->input('tag_list'))) {
            $tag_list=$this->getTagList($request->input('tag_list'), 'App\Models\NewsTag');
            $news->tags()->attach($tag_list);
        }

		//return redirect('hirek');
        return redirect('csoport/'.$news->group->id.'/'.$news->group->slug.'/hirek')->with('message', 'A csoport beszélgetés témát sikeresen módosítottad!');
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

        //dd($news->group->id);

		return view('groupnews.edit', compact('news', 'tags', 'selected_tags'));
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
            'meta_description' => $request->get('meta_description'),
            'body' => $request->get('body'),
            'slug' => Str::slug($request->get('title'))
        ]);

        $news->tags()->sync($tag_list);

        //$group = Group::findOrFail($news->group_id);

        return redirect('csoport/'.$news->group_id.'/'.$news->group->slug.'/hirek')->with('message', 'A csoport beszélgetés témát sikeresen módosítottad!');
	}
}
