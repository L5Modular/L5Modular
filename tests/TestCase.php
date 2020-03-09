<?php

namespace ArtemSchander\L5Modular\Tests;

use ArtemSchander\L5Modular\ModuleServiceProvider;

/**
 * @author Artem Schander
 */
abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (method_exists($this, 'withoutMockingConsoleOutput')) {
            $this->withoutMockingConsoleOutput();
        }
    }

    protected function getPackageProviders($app)
    {
        return [ ModuleServiceProvider::class ];
    }
}
