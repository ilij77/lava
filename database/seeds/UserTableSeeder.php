<?php

use Illuminate\Database\Seeder;
use App\Entity\User;

class UserTableSeeder extends Seeder
{

    public function run()
    {
       factory(User::class,10)->create();
    }
}
