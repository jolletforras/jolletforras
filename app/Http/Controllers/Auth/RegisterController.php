<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Mail;
use App\Rules\ReCaptcha;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'g-recaptcha-response' => ['required', new ReCaptcha]
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
       /* return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
       */



        $data['activation_code']  = Str::random(10);

        $data['slug']=Str::slug($data['name']);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'activation_code' => $data['activation_code'],
            'slug'	=> $data['slug']
        ]);

        $data['id']= $user->id;

        //send verification mail to user
        //--------------------------------------------------------------------------------------------------------------
        Mail::send('auth.emails.welcome', $data, function($message) use ($data)
        {
            $message->from('tarsadalmi.jollet@gmail.com', "tarsadalmijollet.hu");
            $message->subject("email cím megerősítése");
            $message->to($data['email']);
        });

        Mail::send('auth.emails.new_user', $data, function($message) use ($data)
        {
            $message->from('tarsadalmi.jollet@gmail.com', "tarsadalmijollet.hu");
            $message->subject("új regisztráló");
            $message->to('tarsadalmi.jollet@gmail.com');
        });

        return $user;
    }

    /**
     * Activate a new user .
     *
     * @param  integer $id, string  $code
     * @return Response
     */
    public function activate($id, $name, $code)
    {
        $user = User::findOrFail($id);

        if ($user->activation_code==$code) {

            if($user->incompleteProfile()) {
                $user->status=1;
            }
            else {
                $user->status = 3;
            }
            $user->save();

            $message="Sikeresen megerősítetted az email címed! Most már be tudsz jelentkezni.";
            return redirect('login')->with('message', $message);
        }
        else {
            $message="Az email cím megerősítése nem sikerült!";
        }

        return redirect('login')->with('message', $message);

        //return view('auth.activated', compact('$message'));
    }
}
