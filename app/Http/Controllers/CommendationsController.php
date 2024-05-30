<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\TagTrait;
use Illuminate\Http\Request;
use App\Models\Commendation;
use App\Models\CommendationTag;
use App\Models\Group;
use App\Models\GroupTag;
use App\Models\Comment;
use App\Models\Sendemail;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommendationRequest;
use Illuminate\Support\Str;
use Mail;

class CommendationsController extends Controller
{
    use TagTrait;

    private $url_error_msg = ' A hivatkozott oldal bemutató adatait nem sikerült elmenteni! Ennek oka, hogy vagy nincs ilyen adat, vagy nem megfelelő a hivatkozás.';

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

        $group_tags = GroupTag::getCommendationUsed();

        $tags = [''=>''] +$group_tags->pluck('name', 'id')->all();
        $tags_slug = $group_tags->pluck('slug', 'id')->all();

        return view('commendations.index', compact('commendations', 'tags', 'tags_slug'));
    }

    public function show_group_commendations($group_id)
    {
        $group = Group::findOrFail($group_id);
        $commendations = $group->commendations()->where('active', 1)->latest()->get();

        $group_tags = GroupTag::getCommendationUsed();

        $tags = [''=>''] +$group_tags->pluck('name', 'id')->all();
        $tags_slug = $group_tags->pluck('slug', 'id')->all();

        return view('commendations.index', compact('commendations', 'tags', 'tags_slug', 'group'));
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

        $image_url = $commendation->meta_image;
        if($commendation->has_image) {
            $image_url = url('/images/commendations')."/".$commendation->slug.".jpg?".$commendation->photo_counter;
        }

        return view('commendations.show', compact('commendation','image_url','comments'));
    }


    /**
     * Create a commendation
     *
     * @return Response
     */
    public function create() {
        $tags = GroupTag::get()->pluck('name', 'id');

        return view('commendations.create', compact('tags'));
    }

    /**
     * Store a specific commendation
     *
     * @return Response
     */
    public function store(CommendationRequest $request)
    {
        $tag_list=$this->getTagList($request->input('tag_list'), 'App\Models\CommendationTag');

        $title=$meta_image=$description=NULL;
        $extra_msg = '';

        if(!empty($request->get('url'))) {
            libxml_use_internal_errors(true);
            $dom_obj = new \DOMDocument();
            $page_content = @file_get_contents($request->get('url'));
            if($page_content!==FALSE) {
                $dom_obj->loadHTML($page_content);
                $meta_image = $title = $description = $site_name = null;
                $xpath = new \DOMXPath($dom_obj);
                $query = '//*/meta[starts-with(@property, \'og:\')]';
                $metas = $xpath->query($query);
                //foreach($dom_obj->getElementsByTagName('meta') as $meta) {
                foreach ($metas as $meta) {
                    $property = $meta->getAttribute('property');
                    $content = $meta->getAttribute('content');
                    if($property=='og:image') $meta_image = $content;
                    if($property=='og:title') $title = is_numeric(strpos($content,'Ã'))? utf8_decode($content) : $content;
                    if($property=='og:description') $description = is_numeric(strpos($content,'Ã'))? utf8_decode($content) : $content;
                    //if($property=='og:site_name') $site_name = $content;
                }

                if(empty($meta_image)) $extra_msg = " A megadott hivatkozásnál előkép nem tölthető be, ezért az ajánló módosításánál tölthetsz fel képet.";
            }
            else {
                $extra_msg = $this->url_error_msg;
            }
        }

        $commendation = Auth::user()->commendations()->create([
            'title' => $request->get('title'),
            'body' =>  $request->get('body'),
            'url' =>  $request->get('url'),
            'slug' => Str::slug($request->get('title')),
            'public' => $request->has('public') ? 1 : 0,
            'active' => $request->has('active') ? 1 : 0,
            'meta_title' =>  $title,
            'meta_image' =>  $meta_image,
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

            $body = view('commendations.email',$data)->render();

            Sendemail::create([
                'to_email' => 'jolletforras@gmail.com',
                'subject' => "Új ajánló",
                'body' => $body
            ]);

            $message = 'Az új ajánlót sikeresen felvetted, jóváhagyásra vár.';
        }

        $message .= $extra_msg;

        $commendation->tags()->attach($tag_list);

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

        $tags = GroupTag::get()->pluck('name', 'id');
        $selected_tags = $commendation->tags->pluck('id')->toArray();

        return view('commendations.edit', compact('commendation','tags','selected_tags'));
    }

    /**
     * Update a specific forum
     *
     * @param  integer $id The forum ID
     * @return Response
     */
    public function update($id, CommendationRequest $request)
    {
        $tag_list=$this->getTagList($request->input('tag_list'), 'App\Models\CommendationTag');

        $title=$meta_image=$image=$description=NULL;
        $extra_msg = '';

        if(!empty($request->get('url'))) {
            libxml_use_internal_errors(true);
            $dom_obj = new \DOMDocument();
            $page_content = @file_get_contents($request->get('url'));
            if($page_content!==FALSE) {
                $dom_obj->loadHTML($page_content);
                $meta_image = $title = $description = $site_name = null;
                $xpath = new \DOMXPath($dom_obj);
                $query = '//*/meta[starts-with(@property, \'og:\')]';
                $metas = $xpath->query($query);
                //foreach($dom_obj->getElementsByTagName('meta') as $meta) {
                foreach ($metas as $meta) {
                    $property = $meta->getAttribute('property');
                    $content = $meta->getAttribute('content');
                    if($property=='og:image') $meta_image = $content;
                    if($property=='og:title') $title = is_numeric(strpos($content,'Ã'))? utf8_decode($content) : $content;
                    if($property=='og:description') $description = is_numeric(strpos($content,'Ã'))? utf8_decode($content) : $content;
                    //if($property=='og:site_name') $site_name = $content;
                }
            }
            else {
                $extra_msg = $this->url_error_msg;
            }
        }

        $commendation = Commendation::findOrFail($id);
        $prev_slug = $commendation->slug;
        $prev_has_image = $commendation->has_image;


        $slug = Str::slug($request->get('title'));

        $image_file = $request->file('image');
        $has_image = 0;

        //ha most NEM tudja betölteni a képet a hivatkozásból, de adott meg képet, akkor azt elmenti
        if(empty($meta_image) && !empty($image_file)) {
            $imagename=$slug;
            $base_path=base_path().'/public/images/commendations/';
            $tmpimagename = 'tmp_'.$imagename.'.'.$image_file->getClientOriginalExtension();
            $image_file->move($base_path,$tmpimagename);

            $tmpfile=$base_path.$tmpimagename;
            //a maximális magassága a képnek 600
            generateImage($tmpfile, 600, 2, $base_path.$imagename.'.jpg');//1=>width; 2=>height
            unlink($tmpfile);

            $has_image = 1;

            $commendation->photo_counter++;
            $commendation->save();
        }

        //ha most be tudja tölteni a képet a hivatkozásból és korábban volt képe, akkor törli a korábbi képet
        if($prev_has_image && !empty($meta_image)) {
            $base_path=base_path().'/public/images/commendations/';
            unlink($base_path.$prev_slug.'.jpg');
        }

        $commendation->update([
            'title' => $request->get('title'),
            'body' =>  $request->get('body'),
            'url' =>  $request->get('url'),
            'slug' => $slug,
            'public' => $request->has('public') ? 1 : 0,
            'active' => $request->has('active') ? 1 : 0,
            'meta_title' =>  $title,
            'meta_image' =>  $meta_image,
            'meta_description' =>  $description,
            'has_image' =>  $has_image,
        ]);

        if(Auth::user()->admin) {
            $approved = $request->has('approved') ? 1 : 0;
            $commendation->update(['approved' => $approved]);
        }

        $commendation->tags()->sync($tag_list);

        return redirect('ajanlo')->with('message', 'Az ajánlót sikeresen módosítottad!'.$extra_msg);
    }
}
