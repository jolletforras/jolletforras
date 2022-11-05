<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NewsController extends Controller
{
	public function __construct() {
		$this->middleware('auth', ['except'=>['index','show']]);
	}

	public function index(Request $request)
	{
		$newss = News::latest()->get();

		return view('news.index', compact('newss'));
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
		return view('news.create');
	}
	
	public function store(Request $request)
	{
		//Auth::user()->news()->create($request->all());

        $news = Auth::user()->news()->create([
            'title' => $request->get('title'),
            'body' => $request->get('body'),
            'slug' => Str::slug($request->get('title'))
        ]);

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

		return view('news.edit', compact('news'));
	}

	/**
	 * Update a specific news/event
	 *
	 * @param  integer $id The forum ID
	 * @return Response
	 */
	public function update($id, Request $request)
	{
        $news = News::findOrFail($id);

        //$nws->update($request->all());

        $news->update([
            'title' => $request->get('title'),
            'body' => $request->get('body'),
            'slug' => Str::slug($request->get('title'))
        ]);

		return redirect('hirek');
	}
}
