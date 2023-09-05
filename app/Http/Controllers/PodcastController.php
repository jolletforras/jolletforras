<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Podcast;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PodcastController extends Controller
{

    public function __construct() {
        $this->middleware('auth', ['except'=>['index','show']]);
    }


    public function index()
    {
        $podcasts = Podcast::latest()->get();

        return view('podcasts.index', compact('podcasts'));
    }


    /**
     * Displays a specific podcast
     *
     * @param  integer $id The podcast ID
     * @return Response
     */
    public function show($id)
    {
        $podcast = Podcast::findOrFail($id);

        return view('podcasts.show', compact('podcast'));
    }


    /**
     * Create a podcast
     *
     * @return Response
     */
    public function create() {
        return view('podcasts.create');
    }

    /**
     * Store a specific podcast
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $event_id = is_numeric($request->get('event_id')) ? $request->get('event_id') : NULL;
        $group_id = is_numeric($request->get('group_id')) ? $request->get('group_id') : NULL;

        Podcast::create([
            'title' => $request->get('title'),
            'meta_description' => $request->get('meta_description'),
            'url' =>  $request->get('url'),
            'event_id' =>  $event_id,
            'group_id' =>  $group_id,
            'slug' => Str::slug($request->get('title')),
        ]);

        return redirect('az-uj-vilag-hangjai')->with('message', 'Az podcastot sikeresen felvetted!');
    }

    /**
     * Edit a specific podcast
     *
     * @param  integer $id The podcast ID
     * @return Response
     */
    public function edit($id)
    {
        $podcast = Podcast::findOrFail($id);

        if(!Auth::user()->admin) {
            return redirect('/');
        }

        return view('podcasts.edit', compact('podcast'));
    }

    /**
     * Update a specific forum
     *
     * @param  integer $id The forum ID
     * @return Response
     */
    public function update($id, Request $request)
    {
        $podcast = Podcast::findOrFail($id);

        $event_id = is_numeric($request->get('event_id')) ? $request->get('event_id') : NULL;
        $group_id = is_numeric($request->get('group_id')) ? $request->get('group_id') : NULL;

        $podcast->update([
            'title' => $request->get('title'),
            'meta_description' => $request->get('meta_description'),
            'url' =>  $request->get('url'),
            'event_id' =>  $event_id,
            'group_id' =>  $group_id,
            'slug' => Str::slug($request->get('title')),
        ]);

        return redirect('az-uj-vilag-hangjai')->with('message', 'Az podcastot sikeresen felvetted!');
    }
}
