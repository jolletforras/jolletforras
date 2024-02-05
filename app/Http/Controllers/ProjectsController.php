<?php

namespace App\Http\Controllers;

use Validator;
use App\Http\Controllers\Traits\ZipCodeTrait;
use App\Models\Project;
use App\Models\GroupTag;
use App\Models\User;
use App\Models\Comment;
use App\Models\Sendemail;
use App\Http\Requests\ProjectRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Mail;

class ProjectsController extends Controller
{
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
            $projects = Project::latest('created_at')->get();
        }
        else {
            $projects = Project::where('public', 1)->latest('created_at')->get();
        }

        $group_tags = GroupTag::getProjectUsed();

        $tags = [''=>''] +$group_tags->pluck('name', 'id')->all();
        $tags_slug = $group_tags->pluck('slug', 'id')->all();

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

        if(Auth::check()) {
            $members = $project->members()->orderBy('name', 'ASC')->pluck('name', 'user_id');
            $admins = $project->admins()->orderBy('name', 'ASC')->pluck('user_id')->toArray();
            $noadmins = $project->noadmins()->orderBy('name', 'ASC')->pluck('name', 'user_id');

            $comments = Comment::where('commentable_type', 'App\Models\Project')->where('commentable_id', $id)->get();

            return view('projects.show', compact('project','members','admins','noadmins','comments'));
        }
        else {
            if(!$project->public) {                    //belépés oldalra irányít, amennyiben nincs bejelentkezve és nem nyilvános kezdeményezést akar megnyitni
                return redirect('/login');
            }

            return view('projects.show_public', compact('project'));
        }
	}
	
	/**
	 * Create a project
	 *
	 * @return Response
	 */
	public function create() {
		$tags = GroupTag::get()->pluck('name', 'id');

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

        if($project->public) {
            $project->update(['meta_description' => $request->get('meta_description')]);
        }

        //a létrehozó automatikusan résztvevő és kezelő lesz
        $project->members()->attach(Auth::user()->id, ['admin'=>1]);

        $tag_list=$request->input('tag_list');
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

		$tags = GroupTag::get()->pluck('name', 'id');

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

        if($project->public) {
            $project->update(['meta_description' => $request->get('meta_description')]);
        }

        $tag_list=$request->input('tag_list');
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

    public function removeMember($id, Request $request)
    {
        $project = Project::findOrFail($id);

        $project->members()->detach($request->input('remove_member'));

        $response = array(
            'status' => 'success',
        );

        return \Response::json($response);
    }

    public function saveAdmin($id, Request $request)
    {
        //a létrehozó mindig kezelő marad
        DB::table('project_user')->where('project_id',$id)->where('user_id','<>',Auth::user()->id)->update(['admin' => 0]);

        if(!empty($request->input('admin_list'))) {
            DB::table('project_user')->where('project_id', $id)->whereIn('user_id', $request->input('admin_list'))->update(['admin' => 1]);
        }

        $response = array('status' => 'success');

        return \Response::json($response);
    }

    /**
     * User join to a project
     *
     * @param  integer $id The project ID
     * @return Response
     */
    public function join($id, $name, Request $request)
    {
        $project = Project::findOrFail($id);

        if(!$project->isMember()) {
            $user_id = Auth::user()->id;

            $project->members()->attach($user_id);

            //értesítés az kezelőknek
            $admins = $project->admins()->get();

            $data['user_url'] = 'profil/' . $user_id . '/' . Auth::user()->slug;
            $data['user_name'] = Auth::user()->name;

            $data['project_url'] = 'kezdemenyezes/' . $project->id . '/' . $project->slug;
            $data['project_name'] = $project->title;
            $data['subject'] = $project->title." - új tag";

            foreach($admins as $admin) {
                $data['email'] = $admin->email;
                $data['admin_name'] = $admin->name;

                $body = view('projects.emails.new_member_email',$data)->render();

                Sendemail::create([
                    'to_email' => $data['email'],
                    'subject' => $data['subject'],
                    'body' => $body
                ]);
            }
        }

        return redirect('kezdemenyezes/'.$id.'/'.$project->slug)->with('message', 'Innentől a kezdeményezés résztvevőjeként jelensz meg!');
    }

    /**
     * Upload project image
     *
     * @return Response
     */
    public function uploadImage($id,$name)
    {
        return view('projects.upload_image', compact('id','name'));
    }

    /**
     * Save project image
     *
     * @return Response
     */
    public function saveImage($id,$name,Request $request)
    {
        $rules = [
            'image' => 'required|mimes:jpeg,png,gif|max:3072'
        ];

        $messages = [
            'image.required' => 'Képfájl kiválasztása szükséges',
            'image.mimes' => 'A kép fájltípusa .jpg, .png, .gif kell legyen',
            'image.max' => 'A kép nem lehet nagyobb mint :max KB',
        ];

        //dd($request);
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect('kezdemenyezes/'.$id.'/'.$name.'/kepfeltoltes')
                ->withErrors($validator)
                ->withInput();
        }

        $imagename=$id;
        $base_path=base_path().'/public/images/projects/';
        $tmpimagename = 'tmp_'.$imagename.'.'.$request->file('image')->getClientOriginalExtension();
        $request->file('image')->move($base_path,$tmpimagename);

        $tmpfile=$base_path.$tmpimagename;
        generateImage($tmpfile, 400, 1, $base_path.$imagename.'.jpg');//1=>width
        unlink($tmpfile);

        $project = Project::findOrFail($id);
        $project->photo_counter++;
        $project->save();

        return redirect('/kezdemenyezes/'.$id.'/'.$name)->with('message', 'A kezdeményezés képét sikeresen feltöltötted!');
    }
}
