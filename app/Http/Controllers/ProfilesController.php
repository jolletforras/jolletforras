<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\TagTrait;
use App\Http\Controllers\Traits\ZipCodeTrait;
use App\Models\User;
use App\Models\UserSkill;
use App\Models\UserInterest;
use App\Models\Sendemail;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class ProfilesController extends Controller
{
	use TagTrait;
    use ZipCodeTrait;
	
	public function __construct() {
		//$this->middleware('auth');
        $this->middleware('auth', ['except' => ['index','show']]);
	}
	
	public function index()
	{
		//if (empty($type)) $type=config('constants.URL_STORY');

        if(Auth::check())
        {
            $users = User::with('skill_tags')->members()->latest('updated_at')->get();
        }
        else {
            $users = User::with('skill_tags')->members()->latest('updated_at')->where('public','=', 1)->get();
        }

        $city=$district=NULL;

        $user_names = ['0'=>'keresés név szerint'] + User::members()->pluck('name', 'id')->all();

		$skill_tags = ['0'=>'jártasság, tudás - mind'] + UserSkill::active()->pluck('name', 'id')->all();
		$skill_tags_slug = UserSkill::active()->pluck('slug', 'id')->all();

        $interest_tags = ['0'=>'érdeklődés - mind'] + UserInterest::pluck('name', 'id')->all();
        $interest_tags_slug = UserInterest::pluck('slug', 'id')->all();

		return view('profiles.index', compact('users', 'user_names', 'skill_tags', 'skill_tags_slug', 'interest_tags', 'interest_tags_slug', 'city', 'district'));

	}

	//ez most nincs használva
	public function waitingforapprove($type=null)
	{
		$users = User::with('skill_tags')->latest('updated_at')->where('status','=', 2)->get();

		$skill_tags = [''=>''] + UserSkill::pluck('name', 'id')->all();

		$city=$district=$tag_id=NULL;

		$skill_tags_slug = UserSkill::pluck('slug', 'id')->all();

		return view('profiles.index', compact('users', 'skill_tags', 'tag_id', 'skill_tags_slug', 'city', 'district'));

	}

	public function approve($id)
	{
		if(auth::user()->admin) {
			$user = User::findOrFail($id);
			$user->status=3;
			$user->save();

			$data['name'] = $user->name;
			$data['email'] = $user->email;

			Mail::send('auth.emails.approve', $data, function($message) use ($data)
			{
				$message->from('nevalaszolj@tarsadalmijollet.hu', "tarsadalmijollet.hu");
				$message->subject("jelentkezésed jóváhagytuk");
				$message->to($data['email']);
			});
			
			return redirect('jovahagyra_var')->with('message', 'Sikeresen jóváhagytad!');
		}
	}

	public function decline($id)
	{
		if(auth::user()->admin) {
			$user = User::findOrFail($id);

			$user->status=1;
			$user->save();

			$data['name'] = $user->name;
			$data['email'] = $user->email;

			Mail::send('auth.emails.decline', $data, function($message) use ($data)
			{
				$message->from('nevalaszolj@tarsadalmijollet.hu', "tarsadalmijollet.hu");
				$message->subject("jóváhagyásod függőben van");
				$message->to($data['email']);
			});

			return redirect('jovahagyra_var')->with('message', 'Sikeresen elutasítottad!');
		}
	}

	public function filter(Request $request)
	{
		$city=$request->get('city');
		$district=$request->get('district');

		$query = User::members()->latest('updated_at');

		if (isset($city) && $city!="-") {
			$query=$query->where('city','=', $city);
		}


		if (isset($district) && is_numeric($district)) {
			$district<10 ? $dist='0'.$district : $dist=$district;
			$query=$query->where('zip_code', 'like', '1'.$dist.'%');
		}

		$users=$query->get();

		$skill_tags = [''=>''] + UserSkill::pluck('name', 'id')->all();
        $interest_tags = [''=>''] + UserInterest::pluck('name', 'id')->all();

		$returnHTML = view('profiles.partials.members_tabs', compact('users', 'skill_tags', 'interest_tags', 'city', 'district'))->render();

		$response = array(
			'status' => 'success',
			'html' => $returnHTML,
			'count' => count($users)
		);
		return \Response::json($response);
	}

	/**
	 * Displays a specific user profile
	 * 
	 * @param  integer $id The user ID
	 * @return Response
	 */
	public function show($id)
	{
		$user = User::findOrFail($id);

        //ha nincs belépve és nem publikus a profil
        if(!Auth::check() && $user->public==0) return redirect()->guest('login');

        $myprofile = false;
        if(Auth::check()) {
            $myprofile = Auth::user()->id==$user->id;
        }

        if(Auth::check()) {
            $myprofile = Auth::user()->id==$user->id;
        }

        //ha nem saját profil, nincs active (3) státuszban és nem aki megnézni nem bejelentkezett admin
        if(!$myprofile && $user->status!=3 && !(Auth::check() && Auth::user()->admin)) {
            $message = "Jelenleg ".$user->name." adatlapja nem tekinthető meg.";
            return view('no_page', compact('message'));
        }

        $tab = "introduction";

        //megmutatja az összes csoportot, amelyben benne van
        $groups = array();
        foreach($user->member_of_groups as $group) {
            if($group->isActive() || $myprofile) {
                $style = $group->isActive() ? '' : ' style="color: grey;"';
                $groups[] = '<a href="'.url('csoport',$group->id).'/'.$group->slug.'" target="_blank"'.$style.'>'.$group->name.'</a>';
            }
        }

        //megmutatja az összes projektet, amelyben benne van
        $projects  = array();
        foreach($user->member_of_projects as $project) {
                $projects[] = '<a href="'.url('kezdemenyezes',$project->id).'/'.$project->slug.'" target="_blank"'.$style.'>'.$project->title.'</a>';
        }

		return view('profiles.show', compact('user', 'myprofile','tab','groups','projects'));
	}
	
	/**
	 * Edit a specific user profile
	 *
	 * @return Response
	 */
	public function edit()
	{
		$user=Auth::user();
		
		$skill_tags = UserSkill::pluck('name', 'id');
        $selected_skill_tags = $user->skill_tags->pluck('id')->toArray();

        $interest_tags = UserInterest::pluck('name', 'id');
        $selected_interest_tags = $user->interest_tags->pluck('id')->toArray();
		
		return view('profiles.edit', compact('user', 'skill_tags', 'selected_skill_tags', 'interest_tags', 'selected_interest_tags'));
	}
	

	/**
	 * Update a specific user profile
	 *
	 * @param  integer $id The user ID
	 * @return Response
	 */
	public function update($id, ProfileRequest $request)
	{
		$zip_code=$request->get('zip_code');
		$coordinates=$this->getCoordinates($zip_code);

		$skill_tag_list=$this->getTagList((array)$request->input('skill_tag_list'), 'App\Models\UserSkill');
        $interest_tag_list=$this->getTagList((array)$request->input('interest_tag_list'), 'App\Models\UserInterest');

		$user = User::findOrFail($id);
		 
		$name=trim($request->get('name'));

        $introduction = $request->get('introduction');
        $intention = $request->get('intention');
        $interest = $request->get('interest');

		$data = [
			'name' => $name,
			'full_name' => $request->get('full_name'),
			'location' => $request->get('location'),
			'zip_code' => is_numeric($zip_code) ? $zip_code : NULL,
			'lat' => $coordinates['lat'],
			'lng' => $coordinates['lng'],
			'city' => $request->get('city'),
            'webpage_name' => $request->get('webpage_name'),
            'webpage_url' => addhttp($request->get('webpage_url')),
			'introduction' =>$introduction,
            'intention' => $intention,
			'interest' => $interest,
			'slug' => Str::slug($name),
            'public' => $request->has('public') ? 1 : 0
        ];

		if($coordinates) {
			$data['lat'] = $coordinates['lat'];
			$data['lng'] = $coordinates['lng'];
		}

        $user->timestamps = true;
		$user->update($data);

		$user->skill_tags()->sync($skill_tag_list);
        $user->interest_tags()->sync($interest_tag_list);

		$message='Adataidat sikeresen módosítottad!';

		if($user->incompleteProfile()  || !$user->has_photo) {
			$request->session()->flash('warning', true);

			if($user->status!=0) {
				$user->status=1;
				$user->save();
				$request->session()->forget('status_msg');
			}

			return redirect('/profilom/modosit')->with('message', $message);
		}

		if ($user->status==1) {
			$user->status = 3; //teljes tag
			$user->save();
		}

		return redirect('/profil/'.$user->id.'/'.$user->slug)->with('message', $message);
	}

	private function sendApproveRequestEmail($user) {

		$data['name'] = $user->name;
		$data['profil_url']='http://tarsadalmijollet.hu/profil/'.$user->id.'/'.$user->slug;

		$admins = User::admins()->get();
		foreach($admins as $admin) {
			$data['email']=$admin->email;
			Mail::send('auth.emails.approve_request', $data, function($message) use ($data)
			{
				$message->from('nevalaszolj@tarsadalmijollet.hu', "tarsadalmijollet.hu");
				$message->subject("új jóváhagyásra váró");
				$message->to($data['email']);
			});
		}

		/*Mail::send('auth.emails.approve_request', $data, function($message) use ($data)
		{
			$message->from('nevalaszolj@tarsadalmijollet.hu', "tarsadalmijollet.hu");
			$message->subject("új jóváhagyásra váró");
			$message->to('tarsadalmijollet@gmail.com');
		});*/
	}

	/**
	 * Upload user profile image
	 *
	 * @return Response
	 */
	public function uploadImage()
	{
		return view('profiles.upload');
	}


	/**
	 * Save user profile image
	 *
	 * @return Response
	 */
	public function saveImage(Request $request)
	{
		$rules = [
				'image' => 'required|mimes:jpeg,png,gif|max:2048'
		];

		$messages = [
				'image.required' => 'Képfájl kiválasztása szükséges',
				'image.mimes' => 'A kép fájltípusa .jpg, .png, .gif kell legyen',
				'image.max' => 'A kép nem lehet nagyobb mint :max KB',
			];

		//dd($request);
		$validator = Validator::make($request->all(), $rules, $messages);

		if ($validator->fails()) {
			return redirect('profilom/feltolt_profilkep')
			->withErrors($validator)
			->withInput();
		}

		$user=Auth::user();

		$imagename=$user->id;
		$base_path=base_path().'/public/images/profiles/';
		$tmpimagename = 'tmp_'.$imagename.'.'.$request->file('image')->getClientOriginalExtension();
		$request->file('image')->move($base_path,$tmpimagename);

		$tmpfile=$base_path.$tmpimagename;
		generateImage($tmpfile, 400, 1, $base_path.'n_'.$imagename.'.jpg');//1=>width
		generateImage($tmpfile, 60, 0, $base_path.'k_'.$imagename.'.jpg'); //0=>the bigger side
		unlink($tmpfile);

		$user->has_photo=true;
        $user->photo_counter++;

        if (!$user->incompleteProfile()) {
            $user->status = 3; //teljes tag
        }

		$user->save();

		return redirect('/profil/'.$user->id.'/'.$user->slug)->with('message', 'A képedet sikeresen feltöltötted!');
	}


	/**
	 * Changes the user password
	 *
	 * @return Response
	 */
	public function changePassword()
	{
		$user=Auth::user();
		return view('profiles.change_password',compact('user'));
	}

	//itt doesn't work
	/*private function checkRules(Request $request, $rules, $redirect)
	{
		$validator = Validator::make($request->all(),$rules);

		if ($validator->fails()) {
			return redirect($redirect)
			->withErrors($validator)
			->withInput();
		}
	}*/

	/**
	 * Save the user password
	 *
	 * @return Response
	 */
	public function savePassword(Request $request)
	{
		/*$rules = ['password' => 'required|confirmed|min:6'];
		$fails_redirect='profilom/jelszocsere';
		$this->checkRules($request,$rules,'profilom/jelszocsere');*/

		$validator = Validator::make($request->all(), [
				'password' => 'required|confirmed|min:6'
		]);

		if ($validator->fails()) {
			return redirect('profilom/jelszocsere')
			->withErrors($validator)
			->withInput();
		}

		$user=Auth::user();
		$user->password=bcrypt($request->get('password'));
		$user->save();

		return redirect('/profil/'.$user->id.'/'.$user->slug)->with('message', 'Jelszavadat sikeresen módosítottad!');
	}

	/**
	 * Edit the settings
	 *
	 * @return Response
	 */
	public function editSettings()
	{
		$user=Auth::user();
		if ($user->status==3 || $user->status==4) {
            $deactivate = $user->status==3 ? false : true;
            return view('profiles.settings', compact('user', 'deactivate'));
        }
        else {
            return redirect('/profil/'.$user->id.'/'.$user->slug);
        }
	}

	/**
	 * Update the settings
	 *
	 * @return Response
	 */
	public function updateSettings(Request $request)
	{
		$message='Nem történt változtatás.';
		$user=Auth::user();
        $message_r = array();
		if($user->status==3 || $user->status==4) {
			$my_post_comment_notice = empty($request->get('my_post_comment_notice')) ? 0 : 1;
			if($user->my_post_comment_notice != $my_post_comment_notice) {
				if ($my_post_comment_notice) {
                    $message_r[]='Ismét fogsz kapni levélben értesítést, ha hozzá szólnak valamelyik bejegyzésedhez.';
				}
				else {
                    $message_r[]='A továbbiakban nem fogsz levélben kapni értesítést, ha hozzá szólnak valamelyik bejegyzésedhez.';
				}
				$user->my_post_comment_notice=$my_post_comment_notice;
				$user->save();
			}


            $new_post_notice = empty($request->get('new_post_notice')) ? 0 : 1;
            if($user->new_post_notice != $new_post_notice) {
                if ($new_post_notice) {
                    $message_r[]='Ismét fogsz kapni levélben értesítést, ha valamelyik csoportodban létrehoznak egy témát.';
                }
                else {
                    $message_r[]='A továbbiakban nem fogsz levélben értesítést kapni, ha valamelyik csoportodban létrehoznak egy témát.';
                }
                $user->new_post_notice=$new_post_notice;
                $user->save();

                //ha nem kér új téma/esemény (comment_id==0) esetén az email értesítést, akkor csak akkor veszi ki az értesítés, ha külön nem kértek értesítést (de akkor már nem új témaként fog kimenni a levél)
                if ($new_post_notice==0) {
                    $user->notices()->where('comment_id', 0)->where('ask_notice', 0)->update(['email' => 0]);
                }
            }

            $theme_comment_notice = empty($request->get('theme_comment_notice')) ? 0 : 1;
            if($user->theme_comment_notice != $theme_comment_notice) {
                if ($theme_comment_notice) {
                    $message_r[]='Ismét fogsz kapni levélben értesítést, ha valamelyik csoportodban hozzászólnak ahhoz a témához, amihez innentől hozzászólsz.';
                }
                else {
                    $message_r[]='A továbbiakban nem fogsz levélben értesítést kapni, ha valamelyik csoportodban hozzászólnak ahhoz a témához, amihez korábban hozzászóltál.';
                }
                $user->theme_comment_notice=$theme_comment_notice;
                $user->save();

                //ha még új téma (comment_id=0) vagy kérnek rá külön értesítést (ask_notice=1), akkor nem veszi ki az email értesítést
                if ($theme_comment_notice==0) {
                    $user->notices()->where('type', 'Forum')->where('comment_id','<>',0)->where('ask_notice', 0)->update(['email' => 0]); //ahol külön kérnek értesítést hozzászólásra, ott nem törli
                }
            }

            $can_login_with_code = empty($request->get('can_login_with_code')) ? 0 : 1;
            if($user->can_login_with_code != $can_login_with_code) {
                if ($can_login_with_code) {
                    $message_r[]='Ismét 1 napig a levélből a bejegyzést előzetes bejelentkezés nélkül is meg tudod nyitni.';
                }
                else {
                    $message_r[]='Innetől a levélből a bejegyzésed csak akkor fogod tudni megnyitni, ha előzetesen be vagy jelentkezve.';
                }
                $user->can_login_with_code=$can_login_with_code;
                $user->save();
            }

    		$deactivate_prev = $user->status==4 ? 1 : 0;
			$deactivate = empty($request->get('deactivate')) ? 0 : 1;
			if($deactivate!=$deactivate_prev) {
				if ($deactivate) {
                    if($user->isAdminInGroup()) {
                        $message_r[] = 'Nem deaktiválhatod a profilod, amíg valamelyik csoportban kezelő vagy! Előbb töröld magad mint kezelő a csoportokban.';
                    }
				    else {
                        $user->status = 4;
                        $user->deleted_at = Carbon::now();
                        $message_r[] = 'Sikeresen deaktiváltad magad. Amennyiben újból szeretnéd használni az oldalt, vedd ki a pipát a deaktiválásnál.';
                        $user->save();

                        $data['user_id'] = $user->id;
                        $data['user_name'] = $user->name;
                        $body = view('profiles.emails.deactivation',$data)->render();
                        Sendemail::create([
                            'to_email' => 'tarsadalmi.jollet@gmail.com',
                            'subject' => "Deaktiválta magát egy felhasználó",
                            'body' => $body
                        ]);
                    }
				} else {
					$user->status = 3;
					$user->deleted_at = NULL;
                    $message_r[] = 'Sikeresen aktiváltad magad. Újból használhatod az oldalt és látható vagy mások számára.';
					$user->save();
				}
			}
		}

		if(count($message_r) == 1) {
            $message = $message_r[0];
        }
        if(count($message_r) > 1) {
            $message = '- '.implode("<br>- ",$message_r);
        }

		return redirect('/profil/'.$user->id.'/'.$user->slug)->with('message', $message);
	}

}
