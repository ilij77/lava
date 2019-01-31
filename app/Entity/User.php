<?php

namespace App\Entity;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $status
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

    protected $fillable = [
        'name', 'email', 'password','status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',

    ];

    public static function register(string $name, string  $email, string $password):self {
        return static ::create([
            'name'=>$name,
            'email'=>$email,
            'password'=>Hash::make($password),
             'verify_token'=>Str::uuid(),
             'status'=>self::STATUS_WAIT,
        ]);
    }

 public static function new($name,$email):self {
     return static ::create([
         'name'=>$name,
         'email'=>$email,
         'password'=>Hash::make(Str::random()),
         'status'=>self::STATUS_ACTIV,
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

}
