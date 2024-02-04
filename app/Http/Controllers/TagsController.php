<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\ForumTag;
use App\Models\GroupTag;
use App\Models\News;
use App\Models\IdeaSkill;
use App\Models\ProjectTag;
use App\Models\Group;
use App\Models\GroupTheme;
use App\Models\CommendationTag;

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
        $tag = ProjectTag::findOrFail($id);

        $projects=$tag->projects()->latest('updated_at')->get();

        $tags = [''=>''] + ProjectTag::getTagList();

        $tags_slug = ProjectTag::pluck('slug', 'id')->all();

        return view('projects.index', compact('projects', 'tags', 'tags_slug'));
    }

    public function group_show($id) {
        $tag = GroupTag::findOrFail($id);

        $groups=$tag->groups()->where('status','active')->orderBy('name')->get();

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
        $tag = GroupTag::findOrFail($id);
        $group_ids = $tag->groups()->get()->pluck('id')->all();

        $newss=News::whereIN('group_id',$group_ids)->latest('created_at')->get();

        $news_tags = GroupTag::getUsed();
        $tags = [''=>''] + $news_tags->pluck('name', 'id')->all();
        $tags_slug = $news_tags->pluck('slug', 'id')->all();

        return view('news.index', compact('newss', 'tags', 'tags_slug'));
    }

    public function commendation_show($id) {
        $tag = CommendationTag::findOrFail($id);

        $commendations=$tag->commendations()->latest('updated_at')->get();

        $tags = [''=>''] + CommendationTag::getTagList();

        $tags_slug = CommendationTag::pluck('slug', 'id')->all();

        return view('commendations.index', compact('commendations', 'tags', 'tags_slug'));
    }
}
