<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectSkill;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserSkill;
use App\Models\Group;
use App\Models\GroupTag;

class MapController extends Controller
{

    public function members()
    {
        $users = User::members()->whereNotNull('lat')->whereNotNull('lng')->get();

        $initialMarkers = $this->user_markers($users);

        $tags = [''=>''] + UserSkill::pluck('name', 'id')->all();

        $tags_slug = UserSkill::pluck('slug', 'id')->all();

        $map_type = 'tarsak';

        return view('map', compact('initialMarkers', 'map_type', 'tags', 'tags_slug'));
    }

    public function user_skill_show($id) {

        $tag = UserSkill::findOrFail($id);

        $users = $tag->users()->members()->whereNotNull('lat')->whereNotNull('lng')->get();

        $initialMarkers = $this->user_markers($users);

        $tags = [''=>''] + UserSkill::pluck('name', 'id')->all();

        $tags_slug = UserSkill::pluck('slug', 'id')->all();

        $map_type = 'tarsak';

        return view('map', compact('initialMarkers', 'map_type', 'tags', 'tags_slug'));
    }

    public function groups()
    {
        $groups = Group::whereNotNull('lat')->whereNotNull('lng')->get();

        $initialMarkers = $this->group_markers($groups);

        $tags = GroupTag::getTagList();

        $tags_slug = GroupTag::pluck('slug', 'id')->all();

        $map_type = 'csoportok';

        return view('map', compact('initialMarkers','map_type', 'tags', 'tags_slug'));
    }

    public function projects()
    {
        $projects = Project::whereNotNull('lat')->whereNotNull('lng')->get();
        $initialMarkers = $this->project_markers($projects);

        $tags = ProjectSkill::getTagList();
        $tags_slug = ProjectSkill::get()->pluck('slug', 'id')->all();

        $map_type = 'kezdemenyezesek';

        return view('map', compact('initialMarkers','map_type', 'tags', 'tags_slug'));
    }

    public function group_tag_show($id) {

        $tag = GroupTag::find($id);

        $groups = $tag->groups()->whereNotNull('lat')->whereNotNull('lng')->get();

        $initialMarkers = $this->group_markers($groups);

        $tags = GroupTag::getTagList();

        $tags_slug = GroupTag::pluck('slug', 'id')->all();

        $map_type = 'csoportok';

        return view('map', compact('initialMarkers', 'map_type', 'tags', 'tags_slug'));
    }

    public function project_tag_show($id) {

        $tag = ProjectSkill::find($id);

        $projects = $tag->projects()->whereNotNull('lat')->whereNotNull('lng')->get();

        $initialMarkers = $this->project_markers($projects);

        $tags = ProjectSkill::getTagList();
        $tags_slug = ProjectSkill::get()->pluck('slug', 'id')->all();

        $map_type = 'kezdemenyezesek';

        return view('map', compact('initialMarkers','map_type', 'tags', 'tags_slug'));
    }


    protected function user_markers($users)
    {
        $initialMarkers = array();

        foreach($users as $user) {
            $initialMarkers[] = [
                'name' => '<a href="/profil/'.$user->id.'/'.$user->slug.'" style="font-size:12px;">'.$user->name.'</a>',
                'position' => [
                    'lat' => $user->lat+random_int(-50,50)/5000,
                    'lng' => $user->lng+random_int(-50,50)/5000
                ]
            ];
        }

        return $initialMarkers;
    }

    protected function group_markers($groups)
    {
        $initialMarkers = array();

        foreach($groups as $group) {
            $initialMarkers[] = [
                'name' => '<a href="/csoport/'.$group->id.'/'.$group->slug.'" style="font-size:12px;">'.$group->name.'</a>',
                'position' => [
                    'lat' => $group->lat,
                    'lng' => $group->lng
                ]
            ];
        }

        return $initialMarkers;
    }

    protected function project_markers($projects)
    {
        $initialMarkers = array();

        foreach($projects as $project) {
            $initialMarkers[] = [
                'name' => '<a href="/kezdemenyezes/'.$project->id.'/'.$project->slug.'" style="font-size:12px;">'.$project->title.'</a>',
                'position' => [
                    'lat' => $project->lat,
                    'lng' => $project->lng
                ]
            ];
        }

        return $initialMarkers;
    }
}
