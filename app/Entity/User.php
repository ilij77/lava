<?php

namespace App\Entity;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $last_name
 * @property string $email
 * @property string $status
 * @property string $phone
 * @property string $phone_verify_token
 * @property bool $phone_verified
 * @property Carbon $phone_verify_token_expire
 */

class User extends Authenticatable
{
    use Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public const STATUS_WAIT='wait';
    public const STATUS_ACTIV='active';
    public const ROLE_ADMIN='admin';
    public const ROLE_USER='user';

    protected $fillable = [
        'name', 'email','email_verified_at', 'password', 'remember_token',
        'verify_token', 'role', 'status', 'updated_at', 'created_at','last_name',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',

    ];
    protected $casts=[
        'phone_verified'=>'boolean',
        'phone_verify_token_expire'=>'datetime',
    ];

    public static function register(string $name, string  $email, string $password):self {
        return static ::create([
            'name'=>$name,
            'email'=>$email,
            'password'=>Hash::make($password),
             'verify_token'=>Str::uuid(),
             'status'=>self::STATUS_WAIT,
            'role'=>self::ROLE_USER,
        ]);
    }

 public static function new($name,$email):self {
     return static ::create([
         'name'=>$name,
         'email'=>$email,
         'password'=>Hash::make(Str::random()),
         'status'=>self::STATUS_ACTIV,
         'role'=>self::ROLE_USER,
     ]);
 }
 public function isWait():bool{
        return $this->status === self::STATUS_WAIT;
 }
    public function isActive():bool{
        return $this->status === self::STATUS_ACTIV;
    }

    public function verify():void{
        if (!$this->isWait()){
            throw new \DomainException('User is already verified.');
        }
        $this->update([
            'status'=>self::STATUS_ACTIV,
            'verify_token'=>null,
        ]);
    }
    public function changeRole($role):void
    {
        if (!\in_array($role,[self::ROLE_USER,self::ROLE_ADMIN],true)){
            throw new \InvalidArgumentException('Undefined role"' .$role. '"');
        }
        if ($this->role===$role){
            throw new \DomainException('Role is already assigned.');
        }
        $this->update(['role'=>$role]);
    }

    public function isAdmin():bool
    {
        return$this->role===self::ROLE_ADMIN;
    }

    public function unverifyPhone():void
    {
        $this->phone_verified=false;
        $this->phone_verify_token=null;
        $this->phone_verify_token_expire=null;
        $this->saveOrFail();
    }

    public function requestPhoneVerification(Carbon $now):string
    {
        if (empty($this->phone)){
            throw new \DomainException('Phone number is empty.');
        }
        if (!empty($this->phone_verify_token)&&$this->phone_verify_token_expire &&
            $this->phone_verify_token_expire->gt($now)){
            throw new \DomainException('Token is already requested.');
        }
        $this->phone_verified=false;
        $this->phone_verify_token=(string)random_int(10000,99999);
        $this->phone_verify_token_expire=$now->copy()->addSecond(300);
        $this->saveOrFail();

        return $this->phone_verify_token;
    }

    public function verifyPhone($token,Carbon $now):void
    {
        if ($token !==$this->phone_verify_token){
            throw new \DomainException('Incorrect verify token.');
        }
        if ($this->phone_verify_token_expire->lt($now)){
            throw new \DomainException('Token is expired.');
        }
        $this->phone_verified=true;
        $this->phone_verify_token=null;
        $this->phone_verify_token_expire=null;
        $this->saveOrFail();
    }

}
