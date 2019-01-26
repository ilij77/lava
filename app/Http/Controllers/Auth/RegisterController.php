<?php

namespace App\Http\Controllers\Auth;

use App\Mail\VerifyMail;
use App\User;
use App\Http\Controllers\Controller;
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

    use RegistersUsers;

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

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user=User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'verify_token'=>Str::random(),
            'status'=>User::STATUS_WAIT,
        ]);

        \Mail::to($user->email)->send(new VerifyMail($user));
        return $user;


    }
    protected function registered(Request $request, $user)
    {
     $this->guard()->logout();
     return redirect()->route('login')->with('success','Check your email and click on the link to verify.');
    }
}
