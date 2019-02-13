<?php

use Carbon\Carbon;
use Faker\Generator as Faker;
use App\Entity\User;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    $active=$faker->boolean();
    $phoneActive=$faker->boolean();
    return [
        'name' => $faker->name,
        'last_name'=>$faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'phone'=>$faker->unique()->phoneNumber,
        'phone_verified'=>$phoneActive,
        'email_verified_at' => now(),
        'password' => Hash::make('secret'), // secret
        'remember_token' => str_random(10),
        'verify_token'=>$active ? null :\Illuminate\Support\Str::uuid() ,
        'phone_verify_token'=>$phoneActive ? null : Carbon::now(),
        'phone_verify_token_expire'=>$phoneActive ? null : Carbon::now()->addSecond(300),
        'role'=>$active ? $faker->randomElements([User::ROLE_USER,User::ROLE_ADMIN]) :User::ROLE_USER,
        //'role'=>User::ROLE_USER,
        'status'=>$active ? User::STATUS_ACTIV : User::STATUS_WAIT,
    ];
});
