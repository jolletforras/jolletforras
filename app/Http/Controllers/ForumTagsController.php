<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ForumTag;

class ForumTagsController extends Controller
{
    public function show($id) {
		$tag = ForumTag::findOrFail($id);

		$forums=$tag->forums()->latest('updated_at')->get();

		$tags = [''=>''] + ForumTag::lists('name', 'id')->all();

		$tags_slug = ForumTag::lists('slug', 'id')->all();

		return view('forums.index', compact('forums', 'tags', 'tags_slug'));
	}
}
