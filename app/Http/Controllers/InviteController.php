<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Invite;
use App\Models\User;
use Mail;

class InviteController extends Controller
{
    public function create()
    {
        return view('invite.send');
    }

    public function store(Request $request)
    {

        $user=Auth::user();

        $email=$request->get('email');

        if (User::where('email','=', $email)->first())
            return redirect('meghivo/uj')->withInput()->with('message', 'Ezzel az email címmel már regisztráltak.');

        //$data['activation_code']=str_random(10);
        $data['user_name']=$user->name;
        $data['user_email']=$user->email;
        $email = str_replace(' ', '', $email);
        $data['email']=explode(",",$email);
        $data['invite_msg']=$request->get('message');

        /*Invite::create([
            'email' => $email,
            'activation_code' => $data['activation_code'],
            'user_id' => $user->id,
        ]);*/

        Mail::send('invite.email', $data, function($message) use ($data)
        {
            $message->from('tarsadalmi.jollet@gmail.com', "tarsadalmijollet.hu");
            $message->subject("Ismerősöd meghívott a tarsadalmijollet.hu oldalra");
            if(count($data['email'])==1)
                $message->to($data['email']);
            else
                $message->bcc($data['email']);
        });

        return redirect('tarsak')->with('message', 'A meghívód sikeresen el lett küldve!');
    }

    public function activate($code)
    {
        Auth::logout();

        $invite=Invite::where('activation_code','=', $code)->first();

        //dd($invite->email);
        $email=$invite->email;

        return view('auth.register',compact('email'));

    }
}
