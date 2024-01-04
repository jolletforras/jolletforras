<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Creation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Usernotice;
use App\Models\Comment;
use Mail;

class CreationsController extends Controller
{
	public function __construct() {
		$this->middleware('auth', ['except'=>['index','show']]);
	}

	public function index($user_id)
	{
        $user = User::findOrFail($user_id);

        $creations = $user->creations()->latest()->get();

        $tab = "creations";

		return view('creations.index', compact('user','creations','tab'));
	}

    /**
     * Displays a specific creation
     *
     * @param  integer $id The creation ID
     * @return Response
     */
    public function show($id)
    {
        $creation = Creation::findOrFail($id);
        $comments = Comment::where('commentable_type', 'App\Models\Creation')->where('commentable_id', $id)->get();

        if(Auth::check()) {
            $user = Auth()->user();
            $user_id = $user->id;
            $notice = Usernotice::findBy($user_id,$id,'Creation')->first();
            if(is_null($notice)) {
                Usernotice::create(['user_id' => $user_id, 'post_id' => $id, 'type' => 'Creation', 'post_created_at' => $creation->created_at]);
                $user_new_post = $user->user_new_post - 1;
                $user->user_new_post = $user_new_post>0 ? $user_new_post : 0;
                $user->save();
            }
        }

        return view('creations.show', compact('creation','comments'));
    }


	public function create(Request $request) 
	{
    	return view('creations.create');
	}
	
	public function store(Request $request)
	{
        libxml_use_internal_errors(true);
        $dom_obj = new \DOMDocument();
        $page_content = file_get_contents($request->get('url'));
        $dom_obj->loadHTML($page_content);
        $image_src = $og_image = $title = $description = $site_name = null;
        $xpath = new \DOMXPath($dom_obj);
        $query = '//*/meta[starts-with(@property, \'og:\')]';
        $metas = $xpath->query($query);
        //foreach($dom_obj->getElementsByTagName('meta') as $meta) {
        foreach ($metas as $meta) {
            $property = $meta->getAttribute('property');
            $content = $meta->getAttribute('content');
            if($property=='image_src') $image_src = $content;
            if($property=='og:image') $og_image = $content;
            if($property=='og:title') $title = is_numeric(strpos($content,'Ã'))? utf8_decode($content) : $content;
            if($property=='og:description') $description = is_numeric(strpos($content,'Ã'))? utf8_decode($content) : $content;
            //if($property=='og:site_name') $site_name = $content;
        }

        $image = empty($image_src) ? $og_image : $image_src;

        $creation = Auth::user()->creations()->create([
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

        Auth::user()->has_creation = 1;
        Auth::user()->save();

        User::members()->where('id','<>',Auth::user()->id)->increment('user_new_post', 1);

        $data['id']= $creation->id;
        $data['slug']= $creation->slug;
        $data['title']= $creation->title;
        Mail::send('creations.email', $data, function($message) use ($data)
        {
            $message->from('tarsadalmi.jollet@gmail.com', "tarsadalmijollet.hu");
            $message->subject("új alkotás");
            $message->to('tarsadalmi.jollet@gmail.com');
        });

        return redirect('alkotas/'.$creation->id.'/'.$creation->slug)->with('message', 'Az új alkotást sikeresen felvetted.');
	}

	/**
	 * Edit a specific creation
	 *
	 * @param  integer $id The creation ID
	 * @return Response
	 */
	public function edit($id, Request $request)
	{
		$creation = Creation::findOrFail($id);

		if(Auth::user()->id != $creation->user_id) {
			return redirect('/');
		}

		return view('creations.edit', compact('creation'));
	}

	/**
	 * Update a specific creation
	 *
	 * @param  integer $id The creation ID
	 * @return Response
	 */
	public function update($id, Request $request)
    {
        libxml_use_internal_errors(true);
        $dom_obj = new \DOMDocument();
        $page_content = file_get_contents($request->get('url'));
        $dom_obj->loadHTML($page_content);
        $image_src = $og_image = $title = $description = $site_name = null;
        $xpath = new \DOMXPath($dom_obj);
        $query = '//*/meta[starts-with(@property, \'og:\')]';
        $metas = $xpath->query($query);
        //foreach($dom_obj->getElementsByTagName('meta') as $meta) {

        foreach ($metas as $meta) {
            $property = $meta->getAttribute('property');
            $content = $meta->getAttribute('content');
            if($property=='og:image') $og_image = $content;
            if ($property == 'og:title') $title = is_numeric(strpos($content, 'Ã')) ? utf8_decode($content) : $content;
            if ($property == 'og:description') $description = is_numeric(strpos($content, 'Ã')) ? utf8_decode($content) : $content;
            //if($property=='og:site_name') $site_name = $content;
        }

        $metas = $xpath->query('//*/link[starts-with(@rel, \'image_src\')]');
        foreach ($metas as $meta) {
            $rel = $meta->getAttribute('rel');
            $href = $meta->getAttribute('href');
            if($rel=='image_src') $image_src = $href;
        }

        $image = empty($image_src) ? $og_image : $image_src;

        $creation = Creation::findOrFail($id);

        $slug = Str::slug($request->get('title'));
        $creation->update([
            'title' => $request->get('title'),
            'body' => $request->get('body'),
            'url' => $request->get('url'),
            'slug' => Str::slug($request->get('title')),
            'public' => $request->has('public') ? 1 : 0,
            'active' => $request->has('active') ? 1 : 0,
            'meta_title' => $title,
            'meta_image' => $image,
            'meta_description' => $description
        ]);

        return redirect('alkotas/'.$id.'/'.$slug)->with('message', 'Az alkotást sikeresen módosítottad!');
    }    
}
