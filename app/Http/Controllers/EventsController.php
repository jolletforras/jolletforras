<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Event;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EventsController extends Controller
{
	public function __construct() {
		$this->middleware('auth', ['except'=>['index','show']]);
	}

	public function index()
	{
        if(Auth::check())
        {
            $events = Event::latest()->where('visibility','<>', 'group')->get();
        }
        else {
            $events = Event::latest()->where('visibility','=', 'public')->get();
        }

		return view('events.index', compact('events'));
	}

    /**
     * Displays a specific event
     *
     * @param  integer $id The event ID
     * @return Response
     */
    public function show($id)
    {
        $event = Event::findOrFail($id);

        //ha nincs bejelentkezve és az esemény nem nyilvános
        if(!Auth::check() && $event->visibility != 'public') {
            return redirect('/');
        }

        if($event->group_id==0) {
            $has_access = $event->isEditor();
        }
        else {
            $group = Group::findOrFail($event->group_id);

            $has_access = $group->isAdmin();
        }

        return view('events.show', compact('event','has_access'));
    }



	/**
	 * Create a specific event
	 *
	 * @return Response
	 */
	public function create()
	{
        $visibility = ['portal'=>'portál','public'=>'nyilvános'];

		return view('events.create', compact('visibility'));
	}


	/**
	 * Store a specific event
	 *
	 * @param  Request $request
	 * @return Response
	 */
	public function store(Request $request) {

        $event = Auth::user()->events()->create([
            'title' => $request->get('title'),
            'body' => $request->get('body'),
            'slug' => Str::slug($request->get('title')),
            'visibility' => $request->get('visibility'),
            'group_id' => $request->get('group_id'),
            'created_at' => date("Y-m-d H:i:s", strtotime('now'))
        ]);

        if($event->group_id==0) {
            return redirect('esemenyek')->with('message', 'Az új eseményt sikeresen felvetted!');
        }
        else {
            $group = Group::findOrFail($event->group_id);

            return redirect('csoport/'.$event->group_id.'/'.$group->slug.'/esemenyek')->with('message', 'A csoport eseményt sikeresen felvetted!');
        }
	}

	/**
	 * Edit a specific event
	 *
	 * @param  integer $id The forum ID
	 * @return Response
	 */
	public function edit($id)
	{
		$event = Event::findOrFail($id);

		if($event->group_id==0) {
            if(!$event->isEditor()) return redirect('/');

		    $visibility = ['portal'=>'portál','public'=>'nyilvános'];
        } else {
            $group = Group::findOrFail($event->group_id);

            if (!($event->isEditor() || $group->isAdmin())) return redirect('/');

            $visibility = ['group'=>'csoport','portal'=>'portál','public'=>'nyilvános'];
        }

		return view('events.edit', compact('event','visibility'));
	}

	/**
	 * Update a specific event
	 *
	 * @param  integer $id, Request $request
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$event = Event::findOrFail($id);

        $event->update([
            'title' => $request->get('title'),
            'body' => $request->get('body'),
            'slug' => Str::slug($request->get('title')),
            'visibility' => $request->get('visibility'),
            'group_id' => $request->get('group_id'),
            'updated_at' => date("Y-m-d H:i:s", strtotime('now'))
        ]);

        if($event->group_id==0) {
            return redirect('esemenyek')->with('message', 'Az eseményt sikeresen módosítottad!');
        }
        else {
            $group = Group::findOrFail($event->group_id);

            return redirect('csoport/'.$event->group_id.'/'.$group->slug.'/esemenyek')->with('message', 'A csoport eseményt sikeresen módosítottad!');
        }
	}
}
