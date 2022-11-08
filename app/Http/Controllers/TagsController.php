<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\ForumTag;
use App\Models\GroupTag;
use App\Models\NewsTag;

class TagsController extends Controller
{
    public function forum_show($id) {
		$tag = ForumTag::findOrFail($id);

		$forums=$tag->forums()->latest('created_at')->get();

		$tags = [''=>''] + ForumTag::pluck('name', 'id')->all();

		$tags_slug = ForumTag::pluck('slug', 'id')->all();

		return view('forums.index', compact('forums', 'tags', 'tags_slug'));
	}

    public function group_show($id) {
        $tag = GroupTag::findOrFail($id);

        $groups=$tag->groups()->latest('created_at')->get();

        $tags = [''=>''] + GroupTag::pluck('name', 'id')->all();

        $city=NULL;

        $tags_slug = GroupTag::pluck('slug', 'id')->all();

        return view('groups.index', compact('groups', 'tags', 'tags_slug', 'city'));
    }

    public function news_show($id) {
        $tag = NewsTag::findOrFail($id);

        $newss=$tag->news()->latest('created_at')->get();

        $tags = [''=>''] + NewsTag::pluck('name', 'id')->all();

         $tags_slug = NewsTag::pluck('slug', 'id')->all();

        return view('news.index', compact('newss', 'tags', 'tags_slug'));
    }
}
