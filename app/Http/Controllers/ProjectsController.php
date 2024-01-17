<?php

namespace App\Http\Controllers;

use Validator;
use App\Http\Controllers\Traits\TagTrait;
use App\Http\Controllers\Traits\ZipCodeTrait;
use App\Models\Project;
use App\Models\ProjectSkill;
use App\Models\User;
use App\Models\Comment;
use App\Http\Requests\ProjectRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
	use TagTrait;
    use ZipCodeTrait;
	
	public function __construct() {
		$this->middleware('auth', ['except' => ['index','show']]);
	}

	/**
	 * Displays all projects
	 *
	 *
	 * @return Response
	 */
	public function index()
	{
        if(Auth::check()) {
            $projects = Project::with('user', 'members', 'tags')->latest('created_at')->get();
        }
        else {
            $projects = Project::with('user', 'members', 'tags')->latest('created_at')->where('public','=', 1)->get();
        }


		$tags = [''=>''] + ProjectSkill::get()->pluck('name', 'id')->all();

		$tags_slug = ProjectSkill::get()->pluck('slug', 'id')->all();

		return view('projects.index', compact('projects', 'tags', 'tags_slug'));
	}
	
	/**
	 * Displays a specific project
	 *
	 * @param  integer $id The project ID
	 * @return Response
	 */
	public function show($id)
	{
		$project = Project::findOrFail($id);

        $members = $project->members()->orderBy('name', 'ASC')->pluck('name', 'user_id');
        $admins = $project->admins()->orderBy('name', 'ASC')->pluck('user_id')->toArray();
        $is_admin = Auth::check() && in_array(Auth::user()->id, $project->admin_list);

        $comments = Comment::where('commentable_type', 'App\Models\Project')->where('commentable_id', $id)->get();

		return view('projects.show', compact('project','members','admins','is_admin','comments'));
	}
	
	/**
	 * Create a project
	 *
	 * @return Response
	 */
	public function create() {
		$tags = ProjectSkill::get()->pluck('name', 'id');

		$members = User::members()->orderBy('name', 'ASC')->pluck('name','id');

		return view('projects.create', compact('members','tags'));
	}

	/**
	 * Store a specific project
	 *
	 * @return Response
	 */
	public function store(ProjectRequest $request)
	{
	    $tag_list=$this->getTagList($request->input('tag_list'), 'App\Models\ProjectSkill');

        $zip_code=$request->get('zip_code');
        $coordinates=$this->getCoordinates($zip_code);

		$project = Auth::user()->projects()->create([
				'title' => $request->get('title'),
				'body' => $request->get('body'),
				'looking_for' => $request->get('looking_for'),
                'location' => $request->get('location'),
                'zip_code' => $zip_code,
                'lat' => $coordinates['lat'],
                'lng' => $coordinates['lng'],
                'city' => $request->get('city'),
				'slug' => Str::slug($request->get('title')),
                'counter' => 0,
                'public' => $request->has('public') ? 1 : 0
		]);

		$project->members()->attach($request->input('member_list'));

		$project->tags()->attach($tag_list);
		
		return redirect('kezdemenyezesek')->with('message', 'A kezdeményezést sikeresen felvetted!');
	}
	
	/**
	 * Edit a specific project
	 *
	 * @param  integer $id The project ID
	 * @return Response
	 */
	public function edit($id)
	{
		$project = Project::findOrFail($id);

        //ha nem a projekt létrehozója vagy admin
		if(!(Auth::user()->id == $project->user->id || in_array(Auth::user()->id,$project->admin_list))) {
			return redirect('/');
		}

		$members = User::members()->orderBy('name', 'ASC')->pluck('name','id');
        $selected_members = $project->members->pluck('id')->toArray();

		$tags = ProjectSkill::get()->pluck('name', 'id');
        $selected_tags = $project->tags->pluck('id')->toArray();

		return view('projects.edit', compact('project', 'members', 'selected_members', 'tags', 'selected_tags'));
	}
	
	/**
	 * Update a specific project
	 *
	 * @param  integer $id The project ID
	 * @return Response
	 */
	public function update($id, ProjectRequest $request)
	{
		$tag_list=$this->getTagList($request->input('tag_list'), 'App\Models\ProjectSkill');
		
		$project = Project::findOrFail($id);

        $zip_code=$request->get('zip_code');
        $coordinates=$this->getCoordinates($zip_code);

		$project->update([
				'title' => $request->get('title'),
				'body' => $request->get('body'),
				'looking_for' => $request->get('looking_for'),
                'location' => $request->get('location'),
                'zip_code' => $zip_code,
                'lat' => $coordinates['lat'],
                'lng' => $coordinates['lng'],
                'city' => $request->get('city'),
				'slug' => Str::slug($request->get('title')),
                'public' => $request->has('public') ? 1 : 0
		]);

		$project->members()->sync($request->input('member_list'));
	
		$project->tags()->sync($tag_list);
	
		return redirect('kezdemenyezesek')->with('message', 'A kezdeményezést sikeresen módosítottad!');
	}

	/**
	 * Delete a specific project
	 *
	 * @param  integer $id The project ID
	 * @return Response
	 */
	public function delete($id)
	{
		$project = Project::findOrFail($id);

		if(Auth::user()->id != $project->user->id) {
			return redirect('/');
		}

		$project->delete();

		return redirect('kezdemenyezesek')->with('message', 'A kezdeményezést sikeresen törölted!');
	}

    /**
     * Leave a specific project
     *
     * @param  integer $id The project ID
     * @return Response
     */
    public function leave($id)
    {
        $project = Project::findOrFail($id);

        $user_id = Auth::user()->id;

        if($project->user->id == $user_id) {
            return redirect('kezdemenyezesek')->with('message', 'A kezdeményezés létrehozójaként nem tudsz kilépni a kezdeményezésből!');;
        }

        $project->members()->detach($user_id);

        return redirect('kezdemenyezesek')->with('message', 'A kezdeményezésből sikeresen kiléptél!');
    }

    public function saveAdmin($id, Request $request)
    {
        DB::table('project_user')->where('project_id',$id)->update(['admin' => 0]);

        if(!empty($request->input('admin_list'))) {
            DB::table('project_user')->where('project_id', $id)->whereIn('user_id', $request->input('admin_list'))->update(['admin' => 1]);
        }

        $response = array('status' => 'success');

        return \Response::json($response);
    }
}
