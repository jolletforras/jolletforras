<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\TagTrait;
use App\Models\News;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\NewsTag;
use App\Models\Group;

class GroupNewsController extends Controller
{
    use TagTrait;
    private $visibility_options;

    public function __construct() {
		$this->middleware('auth', ['except'=>['index','show']]);
        $this->visibility_options = ['portal'=>'portál','public'=>'nyilvános'];
	}

	public function index($id)
	{
        $group = Group::findOrFail($id);

        //ha nem csoport tag akkor a csoport főoldalára irányít
       /* if(!$group->isMember()) {
            return  redirect('csoport/'.$group->id.'/'.$group->slug);
        }*/

        if(Auth::check()) {
            $newss = News::where('group_id', $group->id)->latest()->get();
        }
        elseif ($group->public){
            $newss = News::where('group_id', $group->id)->where('visibility','public')->latest()->get();
        }
        else {                              //belépés oldalra irányít, amennyiben nincs bejelentkezve és nem nyilvános csoport híreit akarja megnyitni
            return redirect('/login');
        }

        //$tags = [''=>''] + NewsTag::pluck('name', 'id')->all();

        //$tags_slug = NewsTag::pluck('slug', 'id')->all();

        $page = 'news';

		return view('groupnews.index', compact('group', 'page', 'newss'));
	}

    /**
     * Displays a specific article
     *
     * @param  integer $id The article ID
     * @return Response
     */
    public function show($group_id,$group_slug,$news_id,$news_slug)
    {
        $news = News::findOrFail($news_id);

        //ha nem lépett be és nem nyilvános a csoport vagy a hír, akkor bejelentkezés oldalra irányít
        if(Auth::guest() && (!$news->group->public || $news->visibility=='portal')) {
            return redirect('/login');
        }

        return view('groupnews.show', compact('news'));
    }
	
	
	public function create($group_id)
	{
        $visibility_options = $this->visibility_options;

		return view('groupnews.create', compact('group_id','visibility_options'));
	}
	
	public function store(Request $request)
	{
        $news_text = $request->get('body');

        $news = Auth::user()->news()->create([
            'title' => $request->get('title'),
            'meta_description' => $request->get('meta_description'),
            'body' => $news_text,
            'image' => getfirstimage($news_text),
            'slug' => Str::slug($request->get('title')),
            'group_id' => $request->get('group_id'),
            'visibility' => $request->get('visibility')
        ]);

        $news->group->update(['last_news_at' => date('Y-m-d')]);

        return redirect('csoport/'.$news->group->id.'/'.$news->group->slug.'/hirek')->with('message', 'A csoport híreket sikeresen felvetted!');
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

        //csoport kezelői vagy portál admin
        if (!($news->group->isAdmin() || Auth::check() && Auth::user()->admin)) return redirect('csoport/'.$news->group->id.'/'.$news->group->slug.'/hirek')->with('message', 'Csak a csoport kezelői tudják módosítani a híreket!');

        $visibility_options = $this->visibility_options;

		return view('groupnews.edit', compact('news', 'visibility_options'));
	}

	/**
	 * Update a specific news/event
	 *
	 * @param  integer $id The forum ID
	 * @return Response
	 */
	public function update($id, Request $request)
	{
	    $news = News::findOrFail($id);

        $news_text = $request->get('body');

        $news->update([
            'title' => $request->get('title'),
            'meta_description' => $request->get('meta_description'),
            'body' => $news_text,
            'image' => getfirstimage($news_text),
            'slug' => Str::slug($request->get('title')),
            'visibility' => $request->get('visibility')
        ]);

        return redirect('csoport/'.$news->group_id.'/'.$news->group->slug.'/hirek')->with('message', 'A csoport híreket sikeresen módosítottad!');
	}

}
