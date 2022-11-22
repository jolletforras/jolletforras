<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserSkill;
use App\Models\Group;

class MapController extends Controller
{
    protected function markers($users)
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

    public function members()
    {
        $users = User::members()->whereNotNull('lat')->whereNotNull('lng')->get();

        $initialMarkers = $this->markers($users);

        $tags = [''=>''] + UserSkill::pluck('name', 'id')->all();

        $tags_slug = UserSkill::pluck('slug', 'id')->all();

        $map_type = 'tarsak';

        return view('map', compact('initialMarkers', 'map_type', 'tags', 'tags_slug'));
    }

    public function skill_show($id) {

        $tag = UserSkill::findOrFail($id);

        $users = $tag->users()->members()->whereNotNull('lat')->whereNotNull('lng')->get();

        $initialMarkers = $this->markers($users);

        $tags = [''=>''] + UserSkill::pluck('name', 'id')->all();

        $tags_slug = UserSkill::pluck('slug', 'id')->all();

        $map_type = 'tarsak';

        return view('map', compact('initialMarkers', 'map_type', 'tags', 'tags_slug'));
    }

    public function groups()
    {
        $groups = Group::whereNotNull('lat')->whereNotNull('lng')->get();

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

        $map_type = 'csoportok';

        return view('map', compact('initialMarkers','map_type'));
    }
}
