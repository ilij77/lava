<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\RegisterRequest;
use App\Mail\VerifyMail;
use App\Entity\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

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

    //use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/cabinet';

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

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {

        $user=User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'verify_token'=>Str::random(),
            'status'=>User::STATUS_WAIT,
        ]);
        \Mail::to($user->email)->send(new VerifyMail($user));
        event(new Registered($user));
        return redirect()->route('login')->with('success','Check your email and cleck on the link to verify.');
    }

    public function verify($token){
        if (!$user=User::where('verify_token',$token)->first()){
            return redirect()->route('login')->with('error','Sorry your link cannot be identified.');
        }

        if ($user->status !==User::STATUS_WAIT){
            return redirect()->route('login')->with('error','Your email is already verified.');
        }
        $user->status=User::STATUS_ACTIV;
        $user->verify_token=null;
        $user->save();
        return redirect()->route('login')->with('success','Your e-mail is verified. You can now login.');

    }

}
