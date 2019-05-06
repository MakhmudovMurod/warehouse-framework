<?php

namespace Just\Warehouse\Tests\Console;

use Just\Warehouse\Tests\TestCase;

class InstallCommandTest extends TestCase
{
    /** @test */
    public function it_can_install_the_package()
    {
        $this->artisan('warehouse:install')
            ->assertExitCode(0);
    }
}
