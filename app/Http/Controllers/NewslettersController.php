<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Newsletter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NewslettersController extends Controller
{
	public function __construct() {
		$this->middleware('auth', ['except'=>['index','show']]);
	}

	public function index(Request $request)
	{
        $newsletters = Newsletter::latest()->get();

		return view('newsletters.index', compact('newsletters'));
	}

    /**
     * Displays a specific newsletter
     *
     * @param  integer $id The newsletter ID
     * @return Response
     */
    public function show($id)
    {
        $newsletter = Newsletter::findOrFail($id);

        return view('newsletters.show', compact('newsletter'));
    }


	public function create(Request $request) 
	{
        if(!Auth::user()->admin) {
            return redirect('/');
        }

		return view('newsletters.create');
	}
	
	public function store(Request $request)
	{
		//Auth::user()->newsletters()->create($request->all());

        $newsletter = Auth::user()->newsletters()->create([
            'title' => $request->get('title'),
            'body' => $request->get('body'),
            'slug' => Str::slug($request->get('title'))
        ]);

		return redirect('hirlevelek');
	}

	/**
	 * Edit a specific newsletter
	 *
	 * @param  integer $id The newsletter ID
	 * @return Response
	 */
	public function edit($id, Request $request)
	{
		$newsletter = Newsletter::findOrFail($id);

        //Auth::user()->id != $newsletter->user_id
		if(!Auth::user()->admin) {
			return redirect('/');
		}

		return view('newsletters.edit', compact('newsletter'));
	}

	/**
	 * Update a specific newsletter
	 *
	 * @param  integer $id The newsletter ID
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$newsletter = Newsletter::findOrFail($id);

		//$newsletter->update($request->all());

        $newsletter->update([
            'title' => $request->get('title'),
            'body' => $request->get('body'),
            'slug' => Str::slug($request->get('title'))
        ]);

		return redirect('hirlevelek')->with('message', 'A hírlevelet sikeresen módosítottad! - '.$request->get('title'));
	}
}
