<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\UserSkill;
use App\Models\IdeaSkill;
use App\Models\ProjectSkill;
use App\Models\GroupTheme;

class SkillsController extends Controller
{
    public function profiles_show($id) {

		$tag = UserSkill::findOrFail($id);

		$users=$tag->users()->members()->latest('updated_at')->get();

		$city=$district=NULL;

		$tags = [''=>''] + UserSkill::lists('name', 'id')->all();

		$tags_slug = UserSkill::lists('slug', 'id')->all();

		$tag_id=$id;

		return view('profiles.index', compact('users', 'tags', 'tag_id', 'tags_slug', 'city', 'district'));
	}

	public function profiles_filter(Request $request)
	{
		$skill_id=$request->get('skill_id');
		$city=$request->get('city');
		$district=$request->get('district');

		$tag = UserSkill::findOrFail($skill_id);

		$query=$tag->users()->members()->latest('updated_at');

		if (isset($city) && $city!="") {
			$query=$query->where('city','=', $city);
		};

		if (isset($district) && is_numeric($district)) {
			$district<10 ? $dist='0'.$district : $dist=$district;
			$query=$query->where('zip_code', 'like', '1'.$dist.'%');
		}

		$users=$query->get();

		$tags = [''=>''] + UserSkill::lists('name', 'id')->all();

		$returnHTML = view('profiles.partials.members_tabs', compact('users', 'tags', 'city', 'district'))->render();

		$response = array(
			'status' => 'success',
			'html' => $returnHTML,
			'count' => count($users)
		);
		return \Response::json($response);
	}


	public function ideas_show($id) {
		$tag = IdeaSkill::findOrFail($id);

		$ideas=$tag->ideas()->latest('updated_at')->get();

		$tags = [''=>''] + IdeaSkill::lists('name', 'id')->all();

		$tags_slug = IdeaSkill::lists('slug', 'id')->all();

		return view('ideas.index', compact('ideas', 'tags', 'tags_slug'));
	}

	public function projects_show($id) {
		$tag = ProjectSkill::findOrFail($id);

		$projects=$tag->projects()->latest('updated_at')->get();

		$tags = [''=>''] + ProjectSkill::pluck('name', 'id')->all();

		$tags_slug = ProjectSkill::pluck('slug', 'id')->all();

		return view('projects.index', compact('projects', 'tags', 'tags_slug'));
	}

    public function groups_show($id) {
        $tag = GroupTheme::findOrFail($id);

        $groups=$tag->groups()->latest('updated_at')->get();

        $tags = [''=>''] + GroupTheme::lists('name', 'id')->all();

        $tags_slug = GroupTheme::lists('slug', 'id')->all();

        return view('groups.index', compact('groups', 'tags', 'tags_slug'));
    }
}
