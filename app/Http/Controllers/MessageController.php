<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;
use App\Models\User;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function send_message(Request $request)
    {
        $message = $request->get('message');
        $message = htmlspecialchars($message);
        $receiver_id = $request->get('receiver_id');

        $sender = Auth::user();
        $receiver = User::findOrFail($receiver_id);

        //$data['sender_id']=Auth::user()->id;
        $data['sender_url']='profil/'.$sender->id.'/'.$sender->slug;
        $data['sender_name']=$sender->name;
        $data['sender_email']=$sender->email;
        $data['sender_message']=$message;
        $data['sender_message']=$message;

        if($receiver->can_login_with_code) {
            $login_code = Str::random(10);
            $data['sender_url'] = 'email/'.$login_code.'/'.$data['sender_url'];
            $receiver->update(['login_code' => $login_code,'email_sent_at'=>date("Y-m-d H:i:s")]);
        }

        $data['receiver_id']=$receiver_id;
        $data['name']=$receiver->name;
        $data['email']=$receiver->email;
        $data['can_login_with_code'] = $receiver->can_login_with_code;

        Mail::send('profiles.emails.receiver', $data, function($message) use ($data)
        {
            $message->from('tarsadalmi.jollet@gmail.com', "tarsadalmijollet.hu");
            $message->subject($data['sender_name']." írt neked");
            $message->to($data['email']);
        });

        Mail::send('profiles.emails.sender', $data, function($message) use ($data)
        {
            $message->from('tarsadalmi.jollet@gmail.com', "tarsadalmijollet.hu");
            $message->subject($data['name']." üzenetet kapott tőled");
            $message->to($data['sender_email']);
        });

        $response = array('status' => 'success');

        return \Response::json($response);
    }
}
