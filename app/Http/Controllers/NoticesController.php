<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notice;
use Auth;

class NoticesController extends Controller
{
    public function email_theme_login($code,$group_id,$group_slug,$forum_id,$forum_slug) {

        //ha be van jelentkezve, akkor automatikusan mehet az oldalra
        if(Auth::check()) {
            return redirect('/csoport/' . $group_id . '/' . $group_slug . '/tema/' .  $forum_id . '/' . $forum_slug);
        }

        $notice = Notice::where('login_code', $code)->where('notifiable_id', $forum_id)->where('type', 'Forum')->first();

        if($notice) {
            $expiration_time = strtotime($notice->updated_at)+86400;
            if(time()>$expiration_time) {
                return redirect('/login');
            }

            Auth::login($notice->user);
            return redirect('/csoport/' . $group_id . '/' . $group_slug . '/tema/' .  $forum_id . '/' . $forum_slug);
        }
    }

    public function email_event_login($code,$id,$slug) {

        //ha be van jelentkezve, akkor automatikusan mehet az oldalra
        if(Auth::check()) {
            return  redirect('/esemeny/ '. $id . '/' . $slug);
        }

        $notice = Notice::where('login_code', $code)->where('notifiable_id', $id)->where('type', 'Event')->first();
        if($notice) {
            $expiration_time = strtotime($notice->updated_at)+86400;
            if(time()>$expiration_time) {
                return redirect('/login');
            }

            Auth::login($notice->user);
            return  redirect('/esemeny/ '. $id . '/' . $slug);
        }
    }
}
