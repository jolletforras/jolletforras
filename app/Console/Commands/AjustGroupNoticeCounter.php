<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Mail;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AjustGroupNoticeCounter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adjust-group-notice-counter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adjust group notice counter (new_post) for every users.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        User::adjustNewPost();
    }
}


