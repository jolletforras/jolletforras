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

    public function cooperations()
    {
        $groups = Group::whereNotNull('lat')->whereNotNull('lng')->get();
        $initialMarkers_g = $this->group_markers($groups);

        $projects = Project::whereNotNull('lat')->whereNotNull('lng')->get();
        $initialMarkers_p = $this->project_markers($projects);

        $initialMarkers = array_merge($initialMarkers_g,$initialMarkers_p);

        $tags_g = GroupTag::getLocalGroupTagList();
        $tags_p = GroupTag::getLocalProjectTagList();
        $tags = [''=>''] +$tags_g+$tags_p;

        $tags_slug = GroupTag::pluck('slug', 'id')->all();

        $map_type = 'szervezodesek';

        return view('map', compact('initialMarkers','map_type', 'tags', 'tags_slug'));
    }

    public function cooperation_tag_show($id) {

        $tag = GroupTag::find($id);

        $groups = $tag->groups()->whereNotNull('lat')->whereNotNull('lng')->get();
        $initialMarkers_g = $this->group_markers($groups);

        $projects = $tag->projects()->whereNotNull('lat')->whereNotNull('lng')->get();
        $initialMarkers_p = $this->project_markers($projects);

        $initialMarkers = array_merge($initialMarkers_g,$initialMarkers_p);

        $tags_g = GroupTag::getLocalGroupTagList();
        $tags_p = GroupTag::getLocalProjectTagList();
        $tags = [''=>''] +$tags_g+$tags_p;

        $tags_slug = GroupTag::pluck('slug', 'id')->all();

        $map_type = 'szervezodesek';

        return view('map', compact('initialMarkers', 'map_type', 'tags', 'tags_slug'));
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
                ],
                'red' => false
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
                    'lat' => $group->lat+random_int(-50,50)/5000,
                    'lng' => $group->lng+random_int(-50,50)/5000
                ],
                'red' => false
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
                    'lat' => $project->lat+random_int(-50,50)/5000,
                    'lng' => $project->lng+random_int(-50,50)/5000
                ],
                'red' => true
            ];
        }

        return $initialMarkers;
    }
}
