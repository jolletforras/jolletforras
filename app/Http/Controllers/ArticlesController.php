<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ArticlesController extends Controller
{
	public function __construct() {
		$this->middleware('auth', ['except'=>['index','show']]);
        $this->show_options = ['just_profile'=>'csak a profilomnál','portal_too'=>'az Írások menüben is'];
	}

	public function index(Request $request)
	{
		$articles = Article::where('show', 'portal_too')->latest()->get();

		return view('articles.index', compact('articles'));
	}

    public function show_user_articles($user_id)
    {
        $user = User::findOrFail($user_id);
        $articles = $user->articles()->latest()->get();
        $tab = "articles";

        return view('articles.user_articles', compact('user','articles','tab'));
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
        $comments = Comment::where('commentable_type', 'App\Models\Article')->where('commentable_id', $id)->get();

        return view('articles.show', compact('article','comments'));
    }


	public function create(Request $request)
	{
        $show_options = $this->show_options;

		return view('articles.create',compact('show_options'));
	}
	
	public function store(Request $request)
	{
        $description = $request->get('body');

        $article = Auth::user()->articles()->create([
            'title' => $request->get('title'),
            'meta_description' => $request->get('meta_description'),
            'body' => $description,
            'short_description' => justbr($description,700),
            'image' => getfirstimage($description),
            'slug' => Str::slug($request->get('title')),
            'show' => $request->get('show')
        ]);

        Auth::user()->has_article = 1;
        Auth::user()->save();

        if($request->get('show')=='portal_too') {
            return redirect('irasok');
        }
        else {
            return redirect('profil/'.Auth::user()->id.'/'.Auth::user()->slug.'/irasok');
        }
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

        //ha nem a szerző, vagy nem a portál admin, akkor főoldalra irányít
		if(!(Auth::user()->id == $article->user_id || Auth::user()->admin)) {
			return redirect('/');
		}

        $show_options = $this->show_options;

		return view('articles.edit', compact('article','show_options'));
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
            'short_description' => justbr($description,700),
            'image' => getfirstimage($description),
            'slug' => Str::slug($request->get('title')),
            'show' => $request->get('show')
        ]);

        if($request->get('show')=='portal_too') {
            return redirect('irasok')->with('message', 'Az írást sikeresen módosítottad! - '.$request->get('title'));
        }
        else {
            return redirect('profil/'.Auth::user()->id.'/'.Auth::user()->slug.'/irasok')->with('message', 'Az írást sikeresen módosítottad! - '.$request->get('title'));
        }
	}
}
