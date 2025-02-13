<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\TagTrait;
use App\Models\Groupnews;
use App\Models\Projectnews;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\GroupTag;

class NewsController extends Controller
{
    use TagTrait;

    public function __construct() {
		$this->middleware('auth', ['except'=>['groupnews','projectnews','show','groupshow','projectshow']]);
	}
//
	public function groupnews()
	{
        if(Auth::check()) {
            $groupnewss = Groupnews::latest()->get();
        }
        else {
            $groupnewss = Groupnews::where('visibility','public')->latest()->get();
        }

        $group_tags = GroupTag::getGroupNewsUsed();

        $tags = [''=>''] +$group_tags->pluck('name', 'id')->all();
        $tags_slug = $group_tags->pluck('slug', 'id')->all();

		return view('news.group.index', compact('groupnewss', 'tags', 'tags_slug'));
	}

    public function projectnews()
    {
        if(Auth::check()) {
            $projectnewss = Projectnews::latest()->get();
        }
        else {
            $projectnewss = Projectnews::where('visibility','public')->latest()->get();
        }

        $group_tags = GroupTag::getProjectNewsUsed();

        $tags = [''=>''] +$group_tags->pluck('name', 'id')->all();
        $tags_slug = $group_tags->pluck('slug', 'id')->all();

        return view('news.project.index', compact('projectnewss', 'tags', 'tags_slug'));
    }

    /**
     * Displays a specific group news
     *
     * @param  integer $id The article ID
     * @return Response
     */
    public function groupshow($id)
    {
        $news = Groupnews::findOrFail($id);

        //ha nincs belépve és nem publikus a hír
        if(!Auth::check() && $news->visibility!='public') return redirect()->guest('login');

        return view('news.show', compact('news'));
    }


    /**
     * Displays a specific project news
     *
     * @param  integer $id The article ID
     * @return Response
     */
    public function projectshow($id)
    {
        $news = Projectnews::findOrFail($id);

        //ha nincs belépve és nem publikus a hír
        if(!Auth::check() && $news->visibility!='public') return redirect()->guest('login');

        return view('news.show', compact('news'));
    }
	
/*
	public function create(Request $request) 
	{
		return view('news.create');
	}
*/

/*
	public function store(Request $request)
	{
        $news_text = $request->get('body');

        Auth::user()->news()->create([
            'title' => $request->get('title'),
            'meta_description' => $request->get('meta_description'),
            'body' => $news_text,
            'image' => getfirstimage($news_text),
            'slug' => Str::slug($request->get('title'))
        ]);

		return redirect('hirek');
	}
*/
	/**
	 * Edit a specific news
	 *
	 * @param  integer $id The forum ID
	 * @return Response
	 */
/*	public function edit($id, Request $request)
	{
	    $news = News::findOrFail($id);

		if(!(Auth::user()->id == $news->user_id || Auth::user()->admin)) {
			return redirect('/');
		}

		return view('news.edit', compact('news'));
	}
*/
	/**
	 * Update a specific news/event
	 *
	 * @param  integer $id The forum ID
	 * @return Response
	 */
/*	public function update($id, Request $request)
	{
	    $news = News::findOrFail($id);

        //$nws->update($request->all());

        $news_text = $request->get('body');

        $news->update([
            'title' => $request->get('title'),
            'meta_description' => $request->get('meta_description'),
            'body' => $news_text,
            'image' => getfirstimage($news_text),
            'slug' => Str::slug($request->get('title'))
        ]);

		return redirect('hirek');
	}
*/
}

