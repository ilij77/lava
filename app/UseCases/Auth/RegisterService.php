<?php
/**
 * Created by PhpStorm.
 * User: Илья
 * Date: 31.01.2019
 * Time: 1:34
 */

namespace App\UseCases\Auth;


use App\Http\Requests\Auth\RegisterRequest;
use App\Mail\VerifyMail;
use App\Entity\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Mail\Mailer;

class RegisterService
{
    private $mailer;
    private $dispatcher;


    public function __construct(Mailer $mailer,Dispatcher $dispatcher)
    {

        $this->mailer = $mailer;
        $this->dispatcher = $dispatcher;
    }

    public function register(RegisterRequest $request){
        $user=User::register(
            $request['name'],
            $request['email'],
            $request['password']

        );

        $this->mailer->to($user->email)->send(new VerifyMail($user));
        $this->dispatcher->dispatch(new Registered($user));
    }

    public function verify($id):void{
        $user=User::findOrFail($id);
        $user->verify();
    }

}