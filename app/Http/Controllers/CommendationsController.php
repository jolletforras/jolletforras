<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commendation;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommendationRequest;
use Illuminate\Support\Str;

class CommendationsController extends Controller
{
    public function __construct() {
        //$this->middleware('auth');
        $this->middleware('auth', ['except' => ['index','show']]);
    }

    /**
     * Displays all commendations
     *
     *
     * @return Response
     */
    public function index()
    {
        if(Auth::check())
        {
            $commendations = Commendation::latest('updated_at')->get();
        }
        else {
            $commendations = Commendation::where('public', 1)->latest('updated_at')->get();
        }

        //dd($commendations);

        return view('commendations.index', compact('commendations'));
    }

    /**
     * Displays a specific commendation
     *
     * @param  integer $id The commendations ID
     * @return Response
     */
    public function show($id)
    {
        $commendation = Commendation::findOrFail($id);

        $comments = Comment::where('commentable_type', 'App\Models\Commendation')->where('commentable_id', $id)->get();

        return view('commendations.show', compact('commendation','comments'));
    }


    /**
     * Create a commendation
     *
     * @return Response
     */
    public function create() {
        return view('commendations.create');
    }

    /**
     * Store a specific commendation
     *
     * @return Response
     */
    public function store(CommendationRequest $request)
    {
        Auth::user()->commendations()->create([
            'title' => $request->get('title'),
            'body' =>  $request->get('body'),
            'slug' => Str::slug($request->get('title'))
        ]);

        return redirect('ajanlo')->with('message', 'Az új ajánlót sikeresen felvetted!');
    }

    /**
     * Edit a specific commendation
     *
     * @param  integer $id The commendation ID
     * @return Response
     */
    public function edit($id)
    {
        $commendation = Commendation::findOrFail($id);

        if(Auth::user()->id != $commendation->user->id) {
            return redirect('/');
        }

        return view('commendations.edit', compact('commendation'));
    }

    /**
     * Update a specific forum
     *
     * @param  integer $id The forum ID
     * @return Response
     */
    public function update($id, CommendationRequest $request)
    {
        $commendation = Commendation::findOrFail($id);

        $commendation->update([
            'title' => $request->get('title'),
            'body' =>  $request->get('body'),
            'slug' => Str::slug($request->get('title'))
        ]);


        return redirect('ajanlo')->with('message', 'Az ajánlót sikeresen módosítottad!');
    }
}
