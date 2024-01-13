<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Models\Creation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Usernotice;
use App\Models\Comment;
use App\Http\Requests\CreationRequest;
use Mail;

class CreationsController extends Controller
{
    private $url_error_msg = 'A megadott hivatkozás nem megfelelő! Ha nem boldogulsz, küld el a hivatkozásod a tarsadalmi.jollet@gmail.com címre és mi ellenőrizzük.';
    private $source_error_msg = 'Hivatkozást vagy képet szükséges megadnod az alkotásodról.';

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
	
	public function store(CreationRequest $request)
	{

	    $data = $this->getData($request);
        if(isset($data['error_msg']))
            return redirect()->back()->withInput($request->input())->withErrors(['msg' => $data['error_msg']]);

        $creation = Auth::user()->creations($data)->create($data);

        $user = Auth::user();
        $user->has_creation = 1;
        $user->nr_creation_image++;
        $user->save();

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
	public function update($id, CreationRequest $request)
    {
        $creation = Creation::findOrFail($id);
        $data = $this->getData($request,$creation);
        if(isset($data['error_msg']))
            return redirect()->back()->withInput($request->input())->withErrors(['msg' => $data['error_msg']]);

        $creation->update($data);

        return redirect('alkotas/'.$id.'/'.$creation->slug)->with('message', 'Az alkotást sikeresen módosítottad!');
    }


    public function delete($id) {
        $creation = Creation::findOrFail($id);
        $user = $creation->user;
        if(Auth::check() && Auth::user()->id==$user->id) {
            Usernotice::where('post_id',$creation->id)->where('type','Creation')->delete();
            Comment::where('commentable_id',$creation->id)->where('commentable_type','App\Models\Creation')->delete();
            $creation->delete();

            return redirect('/profil/'.$user->id.'/'.$user->slug.'/alkotasok/')->with('message', 'Az alkotást sikeresen törölted.');
        }

        return redirect('/');
    }

    private function getData($request,$creation=null) {
        $url = $request->get('url');
        $image = $request->file('image');
        //akkor is van képe, ha korábban az alkotásnál lett feltöltve kép
        $has_image = !empty($image) ||  isset($creation->has_image) ? 1 : 0;

        if(empty($url)&&empty($image)) {
            //return redirect()->back()->withInput($request->input())->withErrors(['msg' => $this->source_error_msg]);
            //csak akkor számít ha nincs megadva kép, ha új alkotás vagy korábban nem lett feltöltve kép
            if(is_null($creation) || !$creation->has_image) {
                $data['error_msg'] = $this->source_error_msg;
                return $data;
            }
        }

        $image_src = $og_image = $title = $description = $site_name = null;
        if(!empty($url)) {
            libxml_use_internal_errors(true);
            $dom_obj = new \DOMDocument();
            $page_content = @file_get_contents($url);
            if($page_content===FALSE) {
                //return redirect()->back()->withInput($request->input())->withErrors(['msg' => $this->url_error_msg]);
                $data['error_msg'] = $this->url_error_msg;
                return $data;
            }
            $dom_obj->loadHTML($page_content);
            $xpath = new \DOMXPath($dom_obj);
            $query = '//*/meta[starts-with(@property, \'og:\')]';
            $metas = $xpath->query($query);
            //foreach($dom_obj->getElementsByTagName('meta') as $meta) {
            foreach ($metas as $meta) {
                $property = $meta->getAttribute('property');
                $content = $meta->getAttribute('content');
                if($property=='og:image') $og_image = $content;
                if($property=='og:title') $title = is_numeric(strpos($content,'Ã'))? utf8_decode($content) : $content;
                if($property=='og:description') $description = is_numeric(strpos($content,'Ã'))? utf8_decode($content) : $content;
                //if($property=='og:site_name') $site_name = $content;
            }

            $metas = $xpath->query('//*/link[starts-with(@rel, \'image_src\')]');
            foreach ($metas as $meta) {
                $rel = $meta->getAttribute('rel');
                $href = $meta->getAttribute('href');
                if ($rel == 'image_src') $image_src = $href;
            }

            $image = empty($image_src) ? $og_image : $image_src;
        }

        $slug = Str::slug($request->get('title'));

        if(!empty($image)) {
            $imagename=$slug;
            $base_path=base_path().'/public/images/creations/';
            $tmpimagename = 'tmp_'.$imagename.'.'.$image->getClientOriginalExtension();
            $image->move($base_path,$tmpimagename);

            $tmpfile=$base_path.$tmpimagename;
            //a maximális magassága a képnek 600
            generateImage($tmpfile, 600, 2, $base_path.$imagename.'.jpg');//1=>width; 2=>height
            unlink($tmpfile);
        }

        $data = [
            'title' => $request->get('title'),
            'body' =>  $request->get('body'),
            'url' =>  $url,
            'has_image' =>  $has_image,
            'slug' => $slug,
            'public' => $request->has('public') ? 1 : 0,
            'active' => $request->has('active') ? 1 : 0,
            'meta_title' =>  $title,
            'meta_image' =>  $image,
            'meta_description' =>  $description
        ];

        return $data;
    }
}
