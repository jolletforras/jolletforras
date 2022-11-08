<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;

class MapController extends Controller
{
    public function members()
    {
        $users = User::members()->whereNotNull('lat')->whereNotNull('lng')->get();

        $initialMarkers = array();

        foreach($users as $user) {
            $initialMarkers[] = [
                'name' => '<a href="/profil/'.$user->id.'/'.$user->slug.'" style="font-size:12px;">'.$user->name.'</a>',
                'position' => [
                    'lat' => $user->lat,
                    'lng' => $user->lng
                ]
            ];
        }

        $map_type = 'tarsak';

        return view('map', compact('initialMarkers','map_type'));
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
