<?php

namespace App\Console\Commands;

use App\Models\Comment;
use App\Models\Notice;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Mail;
use App\Models\User;
use App\Models\Forum;
use App\Models\Group;
use Illuminate\Support\Facades\DB;
use App\Models\Sendemail;

class SendNoticeEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-notice-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to a group member in different cases';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //!!!!!!addig nem küld ki levelet, amíg a sendemails táblában van kiküldendő levél!!!!!!!!!!
        $sendemails = Sendemail::get();
        if($sendemails->isNotEmpty()) exit;

        $notices = Notice::where('email', 1)->where('email_sent', 0)->oldest('updated_at')->get();

        $nr = 1;
        foreach ($notices as $notice) {
            if($nr>50) exit;

            $notifiable_class = "\\App\Models\\".$notice->type;

            $notifiable = $notifiable_class::find($notice->notifiable_id);

            //ha valamelyikük nem található meg, akkor törli az email értesítést
            if(is_null($notifiable) || is_null($notice->user) ) {
                $notice->update(['email' => 0]);
                continue;
            }

            $group = Group::find($notifiable->group_id);
            if(is_null($group)) {
                $notice->update(['email' => 0]);
                continue;
            }

            $data['name'] = $notice->user->name;
            $data['group_name'] = $group->name;
            $data['post_title'] = $notifiable->title;
            if($notice->type=="Forum") {
                $data['post_url'] = 'csoport/' . $group->id . '/' . $group->slug . '/tema/' . $notifiable->id . '/' . $notifiable->slug;
            }
            else {
                $data['post_url'] = 'esemeny/'. $notifiable->id . '/' . $notifiable->slug;
            }

            if($notice->user->can_login_with_code) {
                $data['post_url'] = 'email/'.$notice->login_code.'/'.$data['post_url'];
            }

            $data['email'] = $notice->user->email;
            $data['user_id'] = $notice->user->id;
            $data['type'] = $notice->type=="Event" ? "esemény" : ($notifiable->type=='announcement' ? "közlemény" :"téma");
            $data['type_txt1'] = $notice->type=="Event" ? "eseményt" : ($notifiable->type=='announcement' ? "közleményt" : "témát");
            $data['type_txt2'] = $notice->type=="Event" ? "eseménynél" : ($notifiable->type=='announcement' ? "közleménynél" : "beszélgetésnél");
            $data['new_post_subject_txt'] = $notice->type=="Forum" && $notifiable->type=='announcement' ? "Közlemény" : "Új ".$data['type'];
            //ezt már nem küldi ki újból
            $notice->update(['email_sent' => 1]);

            $data['can_login_with_code'] = $notice->user->can_login_with_code;

            if($notice->comment_id==0) {     //új téma, közlemény, esemény
                $data['author_name'] = $notifiable->user->name;
                $post = preg_replace("/<img[^>]+\>/i", "", $notifiable->body);
                $data['post'] = $this->get_shorter($post,$data['post_url'],400);
                $email_template = 'groups.emails.new_post_email';
                $data['subject'] = $data['new_post_subject_txt']." a(z) '". $group->name."' csoportodban";
            }
            else {                           //hozzászólás értesítés
                $comment = Comment::find($notice->comment_id);
                //ha nem találja a hozzászólást, akkor továbblép
                if(is_null($comment)) {
                    continue;
                }

                $data['post_url'] .= "#".$notice->comment_id;
                $data['author_name'] = $comment->commenter->name;
                $data['comment'] = $this->get_shorter($comment->body,$data['post_url'],400);
                $data['subject'] = "Új hozzászólás a(z) '".$notifiable->title."' ".$data['type_txt2'];
                $email_template = 'groups.emails.new_comment_email';
            }

            Mail::send($email_template, $data, function ($message) use ($data) {
                $message->from('jolletforras@gmail.com', "jolletforras.hu");
                $message->subject($data['subject']??'');
                $message->to($data['email']??'');
            });

            sleep(3);

            $nr++;
        }
    }

    private function get_shorter($post,$post_url, $length) {
        if(strlen(strip_tags($post))>$length) {
            $post = justbr($post,$length);
            $post .= '<i> ... <a href="https://jolletforras.hu/'.$post_url.'" style="text-decoration: none;">tovább</a></i>';
        }
        return $post;
    }
}


