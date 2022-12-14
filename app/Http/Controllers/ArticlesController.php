<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ArticlesController extends Controller
{
	public function __construct() {
		$this->middleware('auth', ['except'=>['index','show']]);
	}

	public function index(Request $request)
	{
		$articles = Article::latest()->get();

		return view('articles.index', compact('articles'));
	}

    /**
     * Displays a specific article
     *
     * @param  integer $id The article ID
     * @return Response
     */
    public function show($id)
    {
        $article = Article::findOrFail($id);

        return view('articles.show', compact('article'));
    }


	public function create(Request $request) 
	{
		return view('articles.create');
	}
	
	public function store(Request $request)
	{
        $description = $request->get('body');

        $article = Auth::user()->articles()->create([
            'title' => $request->get('title'),
            'meta_description' => $request->get('meta_description'),
            'body' => $description,
            'short_description' => justbr($description,500),
            'image' => getfirstimage($description),
            'slug' => Str::slug($request->get('title'))
        ]);

		return redirect('irasok');
	}

	/**
	 * Edit a specific article
	 *
	 * @param  integer $id The forum ID
	 * @return Response
	 */
	public function edit($id, Request $request)
	{
		$article = Article::findOrFail($id);

		if(Auth::user()->id != $article->user_id) {
			return redirect('/');
		}

		return view('articles.edit', compact('article'));
	}

	/**
	 * Update a specific article/event
	 *
	 * @param  integer $id The forum ID
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$article = Article::findOrFail($id);

        $description = $request->get('body');

        $article->update([
            'title' => $request->get('title'),
            'meta_description' => $request->get('meta_description'),
            'body' => $description,
            'short_description' => justbr($description,500),
            'image' => getfirstimage($description),
            'slug' => Str::slug($request->get('title'))
        ]);

		return redirect('irasok')->with('message', 'Az írást sikeresen módosítottad! - '.$request->get('title'));
	}
}
