<?php

namespace ArtemSchander\L5Modular\Tests;

use ArtemSchander\L5Modular\ModuleServiceProvider;

/**
 * @author Artem Schander
 */
abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
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

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // $app['config']->set('modules.generate', [
        //     'controller' => true,
        //     'model' => true,
        //     'view' => true,
        //     'translation' => false,
        //     'routes' => true,
        //     'migration' => false,
        //     'seeder' => false,
        //     'factory' => false,
        //     'helpers' => false,
        // ]);
    }
}
