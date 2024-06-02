<?php

namespace App\Http\Controllers;

use Validator;
use App\Http\Controllers\Traits\ZipCodeTrait;
use App\Models\Project;
use App\Models\Group;
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
        $projects = Project::orderBy('last_news_at','DESC');

        if(Auth::check()) {
            $projects = $projects->get();
        }
        else {
            $projects = $projects->where('public', 1)->get();
        }

        $group_tags = GroupTag::getProjectUsed();

        $tags = [''=>''] +$group_tags->pluck('name', 'id')->all();
        $tags_slug = $group_tags->pluck('slug', 'id')->all();

        return view('projects.index', compact('projects', 'tags', 'tags_slug'));
	}


    public function show_group_projects($group_id)
    {
        $group = Group::findOrFail($group_id);
        $projects = $group->projects()->where('status', 'active')->latest()->get();

        $group_tags = GroupTag::getProjectUsed();

        $tags = [''=>''] +$group_tags->pluck('name', 'id')->all();
        $tags_slug = $group_tags->pluck('slug', 'id')->all();

        return view('projects.index', compact('projects', 'tags', 'tags_slug', 'group'));
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

        //csak akkor jeleníti meg, ha engedélyezve van vagy bejelentkezés esetén ha saját kezdeményezés, annak kezelője vagy portál admin
        if(!($project->approved || Auth::check() && ($project->isOwner() || $project->isAdmin() || Auth::user()->admin))) {
            return redirect('/');
        }

        $page = 'description';

        if(Auth::check()) {
            $members = $project->members()->orderBy('name', 'ASC')->pluck('name', 'user_id');
            $admins = $project->admins()->orderBy('name', 'ASC')->pluck('user_id')->toArray();
            $noadmins = $project->noadmins()->orderBy('name', 'ASC')->pluck('name', 'user_id');

            $nomembers = $project->isAdmin() ? $project->no_members_list: Null;

            $comments = Comment::where('commentable_type', 'App\Models\Project')->where('commentable_id', $id)->get();

            return view('projects.show', compact('project','members','nomembers','admins','noadmins','comments','page'));
        }
        else {
            if(!$project->public) {                    //belépés oldalra irányít, amennyiben nincs bejelentkezve és nem nyilvános kezdeményezést akar megnyitni
                return redirect('/login');
            }

            return view('projects.show_public', compact('project','page'));
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
            'my_undertake' => $request->get('my_undertake'),
			'looking_for' => $request->get('looking_for'),
            'location' => $request->get('location'),
            'zip_code' => $zip_code,
            'lat' => $coordinates['lat'] ?? NULL,
            'lng' => $coordinates['lng'] ?? NULL,
            'city' => $request->get('city'),
			'slug' => Str::slug($request->get('title')),
            'last_news_at' => date('Y-m-d'),
            'counter' => 0,
            'public' => $request->has('public') ? 1 : 0
		]);

        if($project->public) {
            $project->update(['meta_description' => $request->get('meta_description')]);
        }

        if(Auth::user()->admin) {
            $project->update(['approved' => 1]);
            $message = 'Az új kezdeményezést sikeresen felvetted!';
        }
        else {
            $data['id']= $project->id;
            $data['slug']= $project->slug;
            $data['title']= $project->title;

            $body = view('projects.emails.new_project_email',$data)->render();

            Sendemail::create([
                'to_email' => 'jolletforras@gmail.com',
                'subject' => "Új kezdeményezés",
                'body' => $body
            ]);

            $message = 'Az új kezdeményezést sikeresen felvetted, jóváhagyásra vár.';
        }


        //a létrehozó automatikusan résztvevő és kezelő lesz
        $project->members()->attach(Auth::user()->id, ['admin'=>1]);

        $tag_list=$request->input('tag_list');
		$project->tags()->attach($tag_list);

		return redirect('kezdemenyezesek')->with('message', $message);
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

        //ha nem a projekt létrehozója vagy projekt kezelő vagy portál admin
		if(!($project->isOwner() || $project->isAdmin() || Auth::user()->admin)) {
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
            'my_undertake' => $request->get('my_undertake'),
			'looking_for' => $request->get('looking_for'),
            'location' => $request->get('location'),
            'zip_code' => $zip_code,
            'lat' => $coordinates['lat'] ?? NULL,
            'lng' => $coordinates['lng'] ?? NULL,
            'city' => $request->get('city'),
			'slug' => Str::slug($request->get('title')),
            'public' => $request->has('public') ? 1 : 0,
            'status' => $request->has('inactive') ? 'inactive' : 'active'
		]);

        if($project->public) {
            $project->update(['meta_description' => $request->get('meta_description')]);
        }

        if(Auth::user()->admin) {
            $approved = $request->has('approved') ? 1 : 0;
            $project->update(['approved' => $approved]);
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

    public function invite($id, Request $request)
    {

        $user_id = $request->input('invited_user');

        $response = array(
            'status' => 'success',
            'msg' => $user_id
        );

        if($user_id!=0) {
            $project = Project::findOrFail($id);

            $invitantUser=Auth::user();

            $invitedUser = User::findOrFail($user_id);

            $data['invitant_id']=$invitantUser->id;
            $data['invitant_name']=$invitantUser->name;
            $data['project_id']=$project->id;
            $data['project_name']=$project->title;
            $data['project_slug']=$project->slug;
            $data['invited_name']=$invitedUser->name;
            $data['email']=$invitedUser->email;

            $body = view('projects.emails.invite_email',$data)->render();

            Sendemail::create([
                'to_email' => $data['email'],
                'subject' => "Meghívás a(z) ".$data['project_name']." kezdeményezésbe",
                'body' => $body
            ]);

        }

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
