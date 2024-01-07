<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Mail;
use App\Models\User;
use App\Models\Usernotice;
use App\Models\Article;
use App\Models\Creation;
use Illuminate\Support\Facades\DB;

class AjustUserNoticeCounter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adjust-user-notice-counter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adjust user notice counter (user_new_post) for every users.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $two_weeks_before = date( 'Y-m-d', strtotime('-2 weeks'));

        //törli a 2 hétnél régebbi megnézéseket
        Usernotice::where('post_created_at', '<',$two_weeks_before)->delete();

        $num_articles = Article::where('created_at', '>=',$two_weeks_before)->count();
        $num_creations = Creation::where('created_at', '>=',$two_weeks_before)->count();
        $num_new_posts = $num_articles+$num_creations;

        //beállítja a user_new_post értéket aszerint hogy hány olyan írás, alkotás van, ahol még nem olvasta el a bejegyzést és nem a sajátja
        User::adjustUserNewPostAll($num_new_posts,$two_weeks_before);
    }
}


