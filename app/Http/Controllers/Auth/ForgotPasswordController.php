<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\User;
use Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function newPassword()
    {
        return view('auth.passwords.email');
    }

    public function sendPassword(Request $request)
    {

        $email=$request->get('email');

        $user = User::where('email', $email)->first();
        if(is_null($user)) {
            $message='Ezzel az e-mail címmel nem regisztráltak!';
            return redirect('jelszo/ideiglenes')->with('message', $message);
        }

        $data['password']=Str::random(6);
        $data['email']=$email;
        $data['name']=$user->name;

        Mail::send('auth.emails.password', $data, function($message) use ($data)
        {
            $message->from('tarsadalmi.jollet@gmail.com', "tarsadalmijollet.hu");
            $message->subject("Ideiglenes jelszó");
            $message->to($data['email']);
        });

        $user->password=bcrypt($data['password']);
        $user->save();

        $message="Ideiglenes jelszavadat elküldtük. Ha nem találod, a levélszemét (spam) mappában is nézd  meg.";
        return redirect('login')->with('message', $message);

    }
}
