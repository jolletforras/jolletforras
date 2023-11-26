<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commendation;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommendationRequest;
use Illuminate\Support\Str;
use Mail;

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
            $commendations = Commendation::latest('created_at')->get();
        }
        else {
            $commendations = Commendation::where('public', 1)->latest('created_at')->get();
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

        //csak akkor jeleníti meg, ha aktív és engdélyezve van vagy bejelentkezés esetén ha saját ajánló vagy admin
        if(!($commendation->active && $commendation->approved || Auth::check() && ( Auth::user()->id==$commendation->user->id || Auth::user()->admin))) {
            return redirect('/');
        }

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
        libxml_use_internal_errors(true);
        $dom_obj = new \DOMDocument();
        $page_content = file_get_contents($request->get('url'));
        $dom_obj->loadHTML($page_content);
        $image = $title = $description = $site_name = null;
        $xpath = new \DOMXPath($dom_obj);
        $query = '//*/meta[starts-with(@property, \'og:\')]';
        $metas = $xpath->query($query);
        //foreach($dom_obj->getElementsByTagName('meta') as $meta) {
        foreach ($metas as $meta) {
            $property = $meta->getAttribute('property');
            $content = $meta->getAttribute('content');
            if($property=='og:image') $image = $content;
            if($property=='og:title') $title = is_numeric(strpos($content,'Ã'))? utf8_decode($content) : $content;
            if($property=='og:description') $description = is_numeric(strpos($content,'Ã'))? utf8_decode($content) : $content;
            //if($property=='og:site_name') $site_name = $content;
        }

        $commendation = Auth::user()->commendations()->create([
            'title' => $request->get('title'),
            'body' =>  $request->get('body'),
            'url' =>  $request->get('url'),
            'slug' => Str::slug($request->get('title')),
            'public' => $request->has('public') ? 1 : 0,
            'active' => $request->has('active') ? 1 : 0,
            'meta_title' =>  $title,
            'meta_image' =>  $image,
            'meta_description' =>  $description
        ]);

        if(Auth::user()->admin) {
            $approved = $request->has('approved') ? 1 : 0;
            $commendation->update(['approved' => $approved]);
            $message = 'Az új ajánlót sikeresen felvetted!';
        }
        else {
            $data['id']= $commendation->id;
            $data['slug']= $commendation->slug;
            $data['title']= $commendation->title;
            Mail::send('commendations.email', $data, function($message) use ($data)
            {
                $message->from('tarsadalmi.jollet@gmail.com', "tarsadalmijollet.hu");
                $message->subject("új ajánló");
                $message->to('tarsadalmi.jollet@gmail.com');
            });
            $message = 'Az új ajánlót sikeresen felvetted, jóváhagyásra vár.';
        }

        return redirect('ajanlo')->with('message', $message);
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

        if(Auth::user()->id != $commendation->user->id && !Auth::user()->admin) {
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
        libxml_use_internal_errors(true);
        $dom_obj = new \DOMDocument();
        $page_content = file_get_contents($request->get('url'));
        $dom_obj->loadHTML($page_content);
        $image = $title = $description = $site_name = null;
        $xpath = new \DOMXPath($dom_obj);
        $query = '//*/meta[starts-with(@property, \'og:\')]';
        $metas = $xpath->query($query);
        //foreach($dom_obj->getElementsByTagName('meta') as $meta) {
        foreach ($metas as $meta) {
            $property = $meta->getAttribute('property');
            $content = $meta->getAttribute('content');
            if($property=='og:image') $image = $content;
            if($property=='og:title') $title = is_numeric(strpos($content,'Ã'))? utf8_decode($content) : $content;
            if($property=='og:description') $description = is_numeric(strpos($content,'Ã'))? utf8_decode($content) : $content;
            //if($property=='og:site_name') $site_name = $content;
        }

        $commendation = Commendation::findOrFail($id);

        $commendation->update([
            'title' => $request->get('title'),
            'body' =>  $request->get('body'),
            'url' =>  $request->get('url'),
            'slug' => Str::slug($request->get('title')),
            'public' => $request->has('public') ? 1 : 0,
            'active' => $request->has('active') ? 1 : 0,
            'meta_title' =>  $title,
            'meta_image' =>  $image,
            'meta_description' =>  $description
        ]);

        if(Auth::user()->admin) {
            $approved = $request->has('approved') ? 1 : 0;
            $commendation->update(['approved' => $approved]);
        }


        return redirect('ajanlo')->with('message', 'Az ajánlót sikeresen módosítottad!');
    }
}
