<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;
use App\Models\User;

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

        $receiver = User::findOrFail($receiver_id);

        $data['sender_id']=Auth::user()->id;
        $data['sender_name']=Auth::user()->name;
        $data['sender_message']=$message;
        $data['name']=$receiver->name;
        $data['email']=$receiver->email;

        Mail::send('profiles.email', $data, function($message) use ($data)
        {
            $message->from('tarsadalmi.jollet@gmail.com', "tarsadalmijollet.hu");
            $message->subject($data['sender_name']." írt neked");
            $message->to($data['email']);
        });

        $response = array('status' => 'success');

        return \Response::json($response);
    }
}
