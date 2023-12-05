<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Guide;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GuidesController extends Controller
{
	public function __construct() {
		$this->middleware('auth', ['except'=>['index','show']]);
	}

	public function index(Request $request)
	{
        $guides = Guide::where('id','>',1)->latest()->get();

        $main_guide = Guide::findOrFail(1);

		return view('guides.index', compact('guides','main_guide'));
	}

    /**
     * Displays a specific guide
     *
     * @param  integer $id The guide ID
     * @return Response
     */
    public function show($id)
    {
        $guide = Guide::findOrFail($id);

        return view('guides.show', compact('guide'));
    }


	public function create(Request $request) 
	{
        if(!Auth::user()->admin) {
            return redirect('/');
        }

		return view('guides.create');
	}
	
	public function store(Request $request)
	{
        $description = $request->get('body');

        Guide::create([
            'title' => $request->get('title'),
            'meta_description' => $request->get('meta_description'),
            'short_description' => $request->get('short_description'),
            'body' => $request->get('body'),
            'image' => getfirstimage($description),
            'slug' => Str::slug($request->get('title'))
        ]);

		return redirect('tudnivalok');
	}

	/**
	 * Edit a specific guide
	 *
	 * @param  integer $id The guide ID
	 * @return Response
	 */
	public function edit($id, Request $request)
	{
		$guide = Guide::findOrFail($id);

        //Auth::user()->id != $guide->user_id
		if(!Auth::user()->admin) {
			return redirect('/');
		}

		return view('guides.edit', compact('guide'));
	}

	/**
	 * Update a specific guide
	 *
	 * @param  integer $id The guide ID
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$guide = Guide::findOrFail($id);

        $description = $request->get('body');

        $guide->update([
            'title' => $request->get('title'),
            'meta_description' => $request->get('meta_description'),
            'short_description' => $request->get('short_description'),
            'body' => $request->get('body'),
            'image' => getfirstimage($description),
            'slug' => Str::slug($request->get('title'))
        ]);

		return redirect('tudnivalok')->with('message', 'A tudnivalót sikeresen módosítottad! - '.$request->get('title'));
	}
}
