<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notice;
use App\Models\Forum;
use App\Models\Event;
use Auth;

class NoticesController extends Controller
{
    public function __construct() {
        $this->middleware('auth', ['except' => ['email_theme_login','email_event_login']]);
    }

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

    public function get_noticies()
    {
        $content_html = "";
        $notices = Notice::findNew()->get();
        foreach ($notices as $notice) {
            $new = $notice->new > 0 ? " <span>".$notice->new."</span>" : "";
            if ($notice->type == "Forum") {
                if ($forum = Forum::find($notice->notifiable_id)) {
                    $url = '<a href="csoport/' . $forum->group->id . '/' . $forum->group->slug . '/tema/' . $forum->id . '/' . $forum->slug . '">' . $forum->group->name . ' - "' . $forum->title . '" téma' . $new . '</a>';
                }
            }

            if ($notice->type == "Event") {
                if ($event = Event::find($notice->notifiable_id)) {
                    $url = '<a href="esemeny/' . $event->id . '/' . $event->slug . '">' . $event->group->name . ' - "' . $event->title . '" esemény' . $new . '</a>';
                }
            }
            $content_html .= $notice->new > 0 ? '<b>' . $url . '</b>' : $url;
            $content_html .= '<br ><hr >';
        }

        $response = array(
            'status' => 'success',
            'content_html' => $content_html,
            'notice_counter' =>  Auth::user()->new_post,
        );
        return \Response::json($response);
    }
}
