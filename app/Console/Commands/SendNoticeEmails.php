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
        $notices = Notice::where('email_sent', 0)->oldest('updated_at')->get();

        $nr = 1;
        foreach ($notices as $notice) {
            if($nr>50) exit;

            $notifiable_class = "\\App\Models\\".$notice->type;

            $notifiable = $notifiable_class::find($notice->notifiable_id);
            $user = User::find($notice->user_id);

            //ha valamelyikük nem található meg, akkor törli az értesítést
            if(is_null($notifiable) || is_null($user) ) {
                $notice->delete();
                continue;
            }

            $group = Group::find($notifiable->group_id);
            if(is_null($group)) {
                $notice->delete();
                continue;
            }


            $data['name'] = $user->name;
            $data['group_name'] = $group->name;
            $data['theme_title'] = $notifiable->title;
            $data['theme_url'] = 'csoport/' . $group->id . '/' . $group->slug . '/tema/' . $notifiable->id . '/' . $notifiable->slug;
            $data['email'] = $user->email;
            $data['user_id'] = $user->id;
            $data['theme'] = $notifiable->body;

            //ezt már nem küldi ki újból
            $notice->update(['email_sent' => 1]);

            if($notice->comment_id==0) {     //új téma
                $data['author_name'] = $notifiable->user->name;
                $email_template = 'groupthemes.new_theme_email';
                $data['subject'] = "Új téma a(z) '". $group->name."' csoportodban";
            }
            else {                           //hozzászólás értesítés
                $comment = Comment::find($notice->comment_id);
                //ha nem találja a hozzászólást, akkor továbblép
                if(is_null($comment)) {
                    continue;
                }
                $data['author_name'] = $comment->commenter->name;
                $data['comment'] = $comment->body;
                $data['subject'] = "Új hozzászólás a(z) '".$notifiable->title."' beszélgetésben";
                $email_template = 'groupthemes.new_comment_email';
            }

            Mail::send($email_template, $data, function ($message) use ($data) {
                $message->from('tarsadalmi.jollet@gmail.com', "tarsadalmijollet.hu");
                $message->subject($data['subject']);
                $message->to($data['email']);
            });

            sleep(3);

            $nr++;
        }
    }
}
