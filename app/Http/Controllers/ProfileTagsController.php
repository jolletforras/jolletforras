<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\UserSkill;
use App\Models\UserInterest;
use App\Models\User;

class ProfileTagsController extends Controller
{
    public function profiles_show_by_skill($id) {

		$skill_tag = UserSkill::findOrFail($id);

		$users=$skill_tag->users()->members()->latest('updated_at')->get();

        $user_names = ['0'=>'keresés név szerint'] + User::members()->pluck('name', 'id')->all();

		$city=$district=NULL;

		$skill_tags = ['0'=>'jártasság, tudás - mind'] + UserSkill::pluck('name', 'id')->all();
		$skill_tags_slug = UserSkill::pluck('slug', 'id')->all();
		$skill_tag_id=$id;

        $interest_tags = ['0'=>'érdeklődés - mind'] + UserInterest::pluck('name', 'id')->all();
        $interest_tags_slug = UserInterest::pluck('slug', 'id')->all();

		return view('profiles.index', compact('users', 'user_names', 'skill_tags', 'skill_tag_id', 'skill_tags_slug', 'interest_tags', 'interest_tags_slug', 'city', 'district'));
	}

	public function profiles_filter_by_skill(Request $request)
	{
		$skill_id=$request->get('skill_id');
		$city=$request->get('city');
		$district=$request->get('district');

		$skill_tag = UserSkill::findOrFail($skill_id);
		$query=$skill_tag->users()->members()->latest('updated_at');

		if (isset($city) && $city!="-") {
			$query=$query->where('city','=', $city);
		};

		if (isset($district) && is_numeric($district)) {
			$district<10 ? $dist='0'.$district : $dist=$district;
			$query=$query->where('zip_code', 'like', '1'.$dist.'%');
		}

		$users=$query->get();

		$skill_tags = ['0'=>'jártasság, tudás - mind'] + UserSkill::pluck('name', 'id')->all();

		$returnHTML = view('profiles.partials.members_tabs', compact('users', 'skill_tags', 'city', 'district'))->render();

		$response = array(
			'status' => 'success',
			'html' => $returnHTML,
			'count' => count($users)
		);
		return \Response::json($response);
	}

    public function profiles_show_by_interest($id) {

        $interest_tag = UserInterest::findOrFail($id);

        $users=$interest_tag->users()->members()->latest('updated_at')->get();

        $user_names = ['0'=>'keresés név szerint'] + User::members()->pluck('name', 'id')->all();

        $city=$district=NULL;

        $skill_tags = ['0'=>'jártasság, tudás - mind'] + UserSkill::pluck('name', 'id')->all();
        $skill_tags_slug = UserSkill::pluck('slug', 'id')->all();

        $interest_tags = ['0'=>'érdeklődés - mind'] + UserInterest::pluck('name', 'id')->all();
        $interest_tags_slug = UserInterest::pluck('slug', 'id')->all();
        $interest_tag_id=$id;

        return view('profiles.index', compact('users', 'user_names', 'skill_tags', 'skill_tags_slug', 'interest_tags', 'interest_tag_id', 'interest_tags_slug', 'city', 'district'));
    }

    public function profiles_filter_by_interest(Request $request)
    {
        $interest_id=$request->get('interest_id');
        $city=$request->get('city');
        $district=$request->get('district');

        $interest_tag = UserInterest::findOrFail($interest_id);

        $query=$interest_tag->users()->members()->latest('updated_at');

        if (isset($city) && $city!="-") {
            $query=$query->where('city','=', $city);
        };

        if (isset($district) && is_numeric($district)) {
            $district<10 ? $dist='0'.$district : $dist=$district;
            $query=$query->where('zip_code', 'like', '1'.$dist.'%');
        }

        $users=$query->get();

        $interest_tags = ['0'=>'érdeklődés - mind'] + UserInterest::pluck('name', 'id')->all();

        $returnHTML = view('profiles.partials.members_tabs', compact('users', 'interest_tags', 'city', 'district'))->render();

        $response = array(
            'status' => 'success',
            'html' => $returnHTML,
            'count' => count($users)
        );
        return \Response::json($response);
    }
}
