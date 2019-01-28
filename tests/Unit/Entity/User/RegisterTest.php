<?php
/**
 * Created by PhpStorm.
 * User: Илья
 * Date: 28.01.2019
 * Time: 3:03
 */

namespace Tests\Unit\Entity\User;


use App\Entity\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    public function testRequest():void
    {
        $user=User::register(
            $name='name',
            $email='email',
            $password='password'
        );
        self::assertNotEmpty($user);

        self::assertEquals($name,$user->name);
        self::assertEquals($email,$user->email);

        self::assertNotEmpty($user->password);
        self::assertNotEquals($password,$user->password);

        self::assertTrue($user->isWait);
        self::assetFalse($user->isActive);
    }
    public function testVerify():void
    {
        $user=User::register('name','email','password');
        if ($user->status !==User::STATUS_WAIT){
            return redirect()->route('login')->with('error','Your email is already verified');
        }
        $user->status=User::STATUS_ACTIV;
        $user->verify_token=null;
    $user->save();
    $user->veryfy();
    self::assertFalse($user->isWait);
    self::assertTrue($user->isActive);
    }
    public function testAlreadyVerified():void
    {
        $user=User::register('name','email','password');
        $user->verify();
        $this->expectExceptionMessage('User is already verified');
        $user->verify();

    }


}