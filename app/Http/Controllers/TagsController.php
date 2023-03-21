<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\ForumTag;
use App\Models\GroupTag;
use App\Models\NewsTag;
use App\Models\IdeaSkill;
use App\Models\ProjectSkill;
use App\Models\Group;
use App\Models\GroupTheme;

class TagsController extends Controller
{

    public function ideas_show($id) {
        $tag = IdeaSkill::findOrFail($id);

        $ideas=$tag->ideas()->latest('updated_at')->get();

        $tags = [''=>''] + IdeaSkill::pluck('name', 'id')->all();

        $tags_slug = IdeaSkill::pluck('slug', 'id')->all();

        return view('ideas.index', compact('ideas', 'tags', 'tags_slug'));
    }

    public function projects_show($id) {
        $tag = ProjectSkill::findOrFail($id);

        $projects=$tag->projects()->latest('updated_at')->get();

        $tags = [''=>''] + ProjectSkill::pluck('name', 'id')->all();

        $tags_slug = ProjectSkill::pluck('slug', 'id')->all();

        return view('projects.index', compact('projects', 'tags', 'tags_slug'));
    }

    public function group_show($id) {
        $tag = GroupTag::findOrFail($id);

        $groups=$tag->groups()->orderBy('name')->get();

        $tags = [''=>''] + GroupTag::pluck('name', 'id')->all();

        $city=NULL;

        $tags_slug = GroupTag::pluck('slug', 'id')->all();

        return view('groups.index', compact('groups', 'tags', 'tags_slug', 'city'));
    }

    public function forum_show($id) {
		$tag = ForumTag::findOrFail($id);

		$forums=$tag->forums()->latest('created_at')->get();

		$tags = [''=>''] + ForumTag::pluck('name', 'id')->all();

		$tags_slug = ForumTag::pluck('slug', 'id')->all();

		return view('forums.index', compact('forums', 'tags', 'tags_slug'));
	}

    public function group_theme_show($group_id,$group_slug,$tag_id) {

        $group = Group::findOrFail($group_id);

        $tag = ForumTag::findOrFail($tag_id);

        $forums=$tag->forums()->latest('created_at')->get();

        $tags = [''=>''] + ForumTag::where('group_id', $group_id)->pluck('name', 'id')->all();

        $tags_slug = ForumTag::where('group_id', $group_id)->pluck('slug', 'id')->all();

        $page = 'conversation';
        $status='all';

        return view('groupthemes.index', compact('group','page','status','forums','tags','tags_slug'));
    }

    public function news_show($id) {
        $tag = NewsTag::findOrFail($id);

        $newss=$tag->news()->latest('created_at')->get();

        $news_tags = NewsTag::getTagsOfPublicNews();

        $tags = [''=>''] + $news_tags->pluck('name', 'id')->all();

         $tags_slug = $news_tags->pluck('slug', 'id')->all();

        return view('news.index', compact('newss', 'tags', 'tags_slug'));
    }
}
