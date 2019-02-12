<?php
/**
 * Created by PhpStorm.
 * User: Илья
 * Date: 13.02.2019
 * Time: 1:08
 */

namespace Tests\Unit\Entity\User;


use App\Entity\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PhoneTest  extends TestCase
{
    use DatabaseTransactions;

    public function testDefault(): void
    {
        $user=factory(User::class)->create([
            'phone'=>null,
            'phone_verified'=>false,
            'phone_verify_token'=>null,
        ]);
        self::assertFalse($user->isPhoneVerified());
    }

    public function testRequestPhone():void
    {
        $user=factory(User::class)->create([
            'phone'=>null,
            'phone_verified'=>false,
            'phone_verify_token'=>null,
        ]);
        $this->expectExceptionMessage('Phone number is empty.');
        $user->requestPhoneVerification(Carbon::now());

    }

    public function testRequest():void
    {
        $user=factory(User::class)->create([
            'phone'=>'7900065576',
            'phone_verified'=>false,
            'phone_verify_token'=>null,
        ]);
        $token=$user->requestPhoneVerified(Carbon::now());

        self::assertFalse($user->isPhoneVerified());
        self::assertNotEmpty($token);

    }

    public function testRequestWithOldPhone():void
    {
        $user=factory(User::class)->create([
            'phone'=>'7900065576',
            'phone_verified'=>true,
            'phone_verify_token'=>null,
        ]);
        self::assertTrue($user->isPhoneVerified());
        $user->requestPhoneVerification(Carbon::now());
        self::assetFalse($user->isPhoneVerified());
        self::assertNotEmpty($user->phone_verify_token);
    }

    public function testRequestAlreadySentTimeout():void
    {
        $user=factory(User::class)->create([
            'phone'=>'7900065576',
            'phone_verified'=>true,
            'phone_verify_token'=>null,
        ]);
        $user->requestPhoneVerification($now=Carbon::now());
        $user->requestPhoneVerification($now->copy()->addSeconds(500));
        self::asserFalse($user->isPhoneVerified());

    }

    public function  testRequestAlreadySent():void
    {
        $user=factory(User::class)->create([
            'phone'=>'7900065576',
            'phone_verified'=>true,
            'phone_verify_token'=>null,
        ]);
        $user->requestPhoneVerification($now=Carbon::now());
        $this->expectExceptionMessage('Token is already requested.');
        $user->requestPhoneVerification($now->copy()->addSecond(15));
         }


         public function testVerify():void
         {
             $user=factory(User::class)->create([
                 'phone'=>'7900065576',
                 'phone_verified'=>false,
                 'phone_verify_token'=>$token='token',
                 'phone_verify_token_expire'=>$now=Carbon::now(),
             ]);
             self::assertFalse($user->isPhoneVerified());
             $user->verifyPhone($token,$now->copy()->subSecond(15));
             self::assertTrue($user->isPhoneVerified());

         }

         public function testVerifyIncorrectToken():void
         {
             $user=factory(User::class)->create([
                 'phone'=>'7900065576',
                 'phone_verified'=>false,
                 'phone_verify_token'=>'token',
                 'phone_verify_token_expire'=>$now=Carbon::now(),
             ]);
             $this->expectExceptionMessage('Incorrect verify token.');
             $user->verifyPhone('other_token',$now->copy()->subSecond(15));
         }

         public function  testVerifyExpiredToken():void
         {
             $user=factory(User::class)->create([
                 'phone'=>'7900065576',
                 'phone_verified'=>false,
                 'phone_verify_token'=>$token='token',
                 'phone_verify_token_expire'=>$now=Carbon::now(),
             ]);
             $this->expectExceptionMessage('Token is expired.');
             $user->verifyPhone($token,$now->copy()->addSecond(500));
         }

}