<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\TagTrait;
use App\Models\Projectnews;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\NewsTag;
use App\Models\Project;

class ProjectNewsController extends Controller
{
    use TagTrait;
    private $visibility_options;

    public function __construct() {
		$this->middleware('auth', ['except'=>['index','show']]);
        $this->visibility_options = ['portal'=>'az oldalon','public'=>'nyilvános'];
	}

	public function index($id)
	{
        $project = Project::findOrFail($id);

        if(Auth::check()) {
            $newss = Projectnews::where('project_id', $project->id)->latest()->get();
        }
        elseif ($project->public){
            $newss = Projectnews::where('project_id', $project->id)->where('visibility','public')->latest()->get();
        }
        else {                              //belépés oldalra irányít, amennyiben nincs bejelentkezve és nem nyilvános kezdeményezés híreit akarja megnyitni
            return redirect('/login');
        }

        $page = 'news';

		return view('projectnews.index', compact('project', 'page', 'newss'));
	}

    /**
     * Displays a specific article
     *
     * @param  integer $id The article ID
     * @return Response
     */
    public function show($project_id,$project_slug,$news_id,$news_slug)
    {
        $news = Projectnews::findOrFail($news_id);

        //ha nem lépett be és nem nyilvános a kezdeményezés vagy a hír, akkor bejelentkezés oldalra irányít
        if(Auth::guest() && (!$news->project->public || $news->visibility=='portal')) {
            return redirect('/login');
        }

        return view('projectnews.show', compact('news'));
    }
	
	
	public function create($project_id)
	{
        $visibility_options = $this->visibility_options;

		return view('projectnews.create', compact('project_id','visibility_options'));
	}
	
	public function store(Request $request)
	{
        $news_text = $request->get('body');

        $news = Auth::user()->projectnews()->create([
            'title' => $request->get('title'),
            'meta_description' => $request->get('meta_description'),
            'body' => $news_text,
            'image' => getfirstimage($news_text),
            'slug' => Str::slug($request->get('title')),
            'project_id' => $request->get('project_id'),
            'visibility' => $request->get('visibility')
        ]);

        $news->project->update(['last_news_at' => date('Y-m-d')]);

        return redirect('kezdemenyezes/'.$news->project->id.'/'.$news->project->slug.'/hirek')->with('message', 'A kezdeményezés híreket sikeresen felvetted!');
	}

	/**
	 * Edit a specific news
	 *
	 * @param  integer $id The forum ID
	 * @return Response
	 */
	public function edit($id, Request $request)
	{
	    $news = Projectnews::findOrFail($id);

        //kezdeményezés kezelői vagy portál admin
        if (!($news->project->isAdmin() || Auth::check() && Auth::user()->admin)) return redirect('kezdemenyezes/'.$news->project->id.'/'.$news->project->slug.'/hirek')->with('message', 'Csak a kezdeményezés kezelői tudják módosítani a híreket!');

        $visibility_options = $this->visibility_options;

		return view('projectnews.edit', compact('news', 'visibility_options'));
	}

	/**
	 * Update a specific news/event
	 *
	 * @param  integer $id The forum ID
	 * @return Response
	 */
	public function update($id, Request $request)
	{
	    $news = Projectnews::findOrFail($id);

        $news_text = $request->get('body');

        $news->update([
            'title' => $request->get('title'),
            'meta_description' => $request->get('meta_description'),
            'body' => $news_text,
            'image' => getfirstimage($news_text),
            'slug' => Str::slug($request->get('title')),
            'visibility' => $request->get('visibility')
        ]);

        return redirect('kezdemenyezes/'.$news->project_id.'/'.$news->project->slug.'/hirek')->with('message', 'A kezdeményezés híreket sikeresen módosítottad!');
	}

}
