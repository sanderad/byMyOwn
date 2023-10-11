<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    /** @test */
    public function it_registers_a_route(): void
    {
        // given that we have a router object
        $router = new router();

        // when we call a register method and provide the arguments
        $router->register('get', '/users', ['Users', 'index']);
        $expected = [
            'get' => [
                '/users' => ['Users', 'index']
            ]
            ];
        // then we assert route was registered
        $this->assertEquals($expected, $router->routes());
    }
}