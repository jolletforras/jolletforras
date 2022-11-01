<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\GroupTag;

class GroupTagsController extends Controller
{
    public function show($id) {
		$tag = GroupTag::findOrFail($id);

		$groups=$tag->groups()->latest('updated_at')->get();

		$tags = [''=>''] + GroupTag::pluck('name', 'id')->all();

        $city=NULL;

		$tags_slug = GroupTag::pluck('slug', 'id')->all();

		return view('groups.index', compact('groups', 'tags', 'tags_slug', 'city'));
	}
}
