<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
	public function __construct() {
		$this->middleware('auth', ['except'=>['index']]);
	}

	public function index(Request $request)
	{
		$news = News::latest()->get();

		return view('news.index', compact('news'));
	}
	
	
	public function create(Request $request) 
	{
		return view('news.create');
	}
	
	public function store(Request $request)
	{
		Auth::user()->news()->create($request->all());

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
        $nws = News::findOrFail($id);

        $nws->update($request->all());

		return redirect('hirek');
	}
}
