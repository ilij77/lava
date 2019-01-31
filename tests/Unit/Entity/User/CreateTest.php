<?php

namespace Tests\Unit\Entity\User;

use App\Entity\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use DatabaseTransactions;

    public function testNew(): void
    {
        $user = User::new(
            'name4',
             'email4'
        );

        self::assertNotEmpty($user);

        self::assertEquals('name4', $user->name);
        self::assertEquals('email4', $user->email);
        self::assertNotEmpty($user->password);

        self::assertTrue($user->isActive());

    }
}
