<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\ForumTag;
use App\Models\GroupTag;
use App\Models\Groupnews;
use App\Models\Projectnews;
use App\Models\IdeaSkill;
use App\Models\project;
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

        $tag = GroupTag::findOrFail($id);

        $projects=$tag->projects()->latest('updated_at')->get();

        $group_tags = GroupTag::getProjectUsed();
        $tags = [''=>''] +$group_tags->pluck('name', 'id')->all();
        $tags_slug = $group_tags->pluck('slug', 'id')->all();

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

    public function group_news_show($id) {
        $tag = GroupTag::findOrFail($id);
        $group_ids = $tag->groups()->get()->pluck('id')->all();

        $newss=Groupnews::whereIN('group_id',$group_ids)->latest('created_at')->get();

        $group_tags = GroupTag::getGroupNewsUsed();
        $tags = [''=>''] + $group_tags->pluck('name', 'id')->all();
        $tags_slug = $group_tags->pluck('slug', 'id')->all();

        return view('news.group.index', compact('newss', 'tags', 'tags_slug'));
    }

    public function project_news_show($id) {
        $tag = GroupTag::findOrFail($id);
        $project_ids = $tag->projects()->get()->pluck('id')->all();

        $newss=Projectnews::whereIN('project_id',$project_ids)->latest('created_at')->get();

        $group_tags = GroupTag::getProjectNewsUsed();
        $tags = [''=>''] + $group_tags->pluck('name', 'id')->all();
        $tags_slug = $group_tags->pluck('slug', 'id')->all();

        return view('news.project.index', compact('newss', 'tags', 'tags_slug'));
    }

    public function commendation_show($id) {
        $tag = GroupTag::findOrFail($id);

        $commendations=$tag->commendations()->latest('updated_at')->get();

        $group_tags = GroupTag::getCommendationUsed();
        $tags = [''=>''] +$group_tags->pluck('name', 'id')->all();
        $tags_slug = $group_tags->pluck('slug', 'id')->all();

        return view('commendations.index', compact('commendations', 'tags', 'tags_slug'));
    }
}
