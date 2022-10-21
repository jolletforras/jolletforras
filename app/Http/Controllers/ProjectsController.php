<?php

namespace App\Http\Controllers;

use Validator;
use App\Http\Controllers\Traits\TagTrait;
use App\Models\Project;
use App\Models\ProjectSkill;
use App\Models\User;
use App\Models\Comment;
use App\Http\Requests\ProjectRequest;
use Illuminate\Support\Facades\Auth;

class ProjectsController extends Controller
{
	use TagTrait;
	
	public function __construct() {
		$this->middleware('auth');
	}

	/**
	 * Displays all projects
	 *
	 *
	 * @return Response
	 */
	public function index()
	{
		$projects = Project::with('user', 'members', 'tags')->latest('updated_at')->get();

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

        $comments = Comment::where('commentable_type', 'App\Project')->where('commentable_id', $id)->get();
	
		return view('projects.show', compact('project','comments'));
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

		$project = Auth::user()->projects()->create([
				'title' => $request->get('title'),
				'body' => $request->get('body'),
				'looking_for' => $request->get('looking_for'),
				'slug' => slugify($request->get('title'))
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
		
		if(Auth::user()->id != $project->user->id) {
			return redirect('/');
		}

		$members = User::members()->orderBy('name', 'ASC')->pluck('name','id');

		$tags = ProjectSkill::get()->pluck('name', 'id');

		return view('projects.edit', compact('project', 'members', 'tags'));
	}
	
	/**
	 * Update a specific project
	 *
	 * @param  integer $id The project ID
	 * @return Response
	 */
	public function update($id, ProjectRequest $request)
	{
		$tag_list=$this->getTagList($request->input('tag_list'), 'App\ProjectSkill');
		
		$project = Project::findOrFail($id);

		$project->update([
				'title' => $request->get('title'),
				'body' => $request->get('body'),
				'looking_for' => $request->get('looking_for'),
				'slug' => slugify($request->get('title'))
		]);

		$project->members()->sync($request->input('member_list'));
	
		$project->tags()->sync($tag_list);
	
		return redirect('kezdemenyezesek')->with('message', 'A kezdeményezést sikeresen módosítottad!');
	}

	/**
	 * Delete a specific idea
	 *
	 * @param  integer $id The idea ID
	 * @return Response
	 */
	public function delete($id)
	{
		$project = Project::findOrFail($id);

		if(Auth::user()->id != $project->user->id) {
			return redirect('/');
		}

		$project->delete();

		return redirect('projektek')->with('message', 'A kezdeményezést sikeresen törölted!');
	}
}
