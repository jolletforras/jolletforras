<?php

namespace App\Console\Commands;

use App\Models\Comment;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Mail;
use App\Models\User;
use App\Models\Forum;
use App\Models\Group;
use Illuminate\Support\Facades\DB;

class SendEmailNewGroupTheme extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-email-new-group-theme';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to a group member in case of new theme';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $notices = DB::table('forum_user')->where('email_sent', 0)->oldest('updated_at')->get();

        $nr = 1;
        foreach ($notices as $notice) {
            if($nr>50) exit;

            $forum = Forum::find($notice->forum_id);
            $user = User::find($notice->user_id);
            $group = Group::find($forum->group_id);

            if(is_null($forum) || is_null($user) || is_null($group) ) {
                DB::table('forum_user')->delete($notice->id);
                continue;
            }


            $data['name'] = $user->name;
            $data['group_name'] = $group->name;
            $data['theme_title'] = $forum->title;
            $data['theme_url'] = 'csoport/' . $group->id . '/' . $group->slug . '/tema/' . $forum->id . '/' . $forum->slug;
            $data['email'] = $user->email;
            $data['user_id'] = $user->id;
            $data['theme'] = $forum->body;

            if($notice->new==1) {
                $data['author_name'] = $forum->user->name;
                $email_template = 'groupthemes.new_theme_email';
                DB::table('forum_user')->delete($notice->id);
                $data['subject'] = "Új téma a(z) '". $group->name."' csoportodban";
            }
            else {
                //hozzászólás értesítés
                $comment = Comment::find($notice->comment_id);
                if(is_null($comment)) {
                    DB::table('forum_user')->delete($notice->id);
                    continue;
                }
                $data['author_name'] = $comment->commenter->name;
                $data['comment'] = $comment->body;
                $data['subject'] = "Új hozzászólás a(z) '".$forum->title."' beszélgetésben";
                $email_template = 'groupthemes.new_comment_email';

                DB::table('forum_user')->where('forum_id',$notice->forum_id)->where('user_id',$notice->user_id)->update(['email_sent' => 1,'updated_at' => date("Y-m-d H:i:s", strtotime('now'))]); //ezt már nem küldi ki újból
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
