<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use App\Models\Sendemail;
use Mail;

class SendStoredEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-stored-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email stored in sendemails table';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sendemails = Sendemail::oldest('updated_at')->limit(50)->get();

        foreach ($sendemails as $email) {
            $data = $email->toArray();

            Mail::send([], $data, function ($message) use ($data) {
                $message->from('tarsadalmi.jollet@gmail.com', "tarsadalmijollet.hu");
                $message->subject($data['subject']);
                $message->setBody($data['body'],'text/html');
                $message->to($data['to_email']);
            });

            $email->delete();

            sleep(3);
        }
    }

}


