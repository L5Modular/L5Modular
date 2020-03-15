<?php

namespace ArtemSchander\L5Modular\Tests\Commands;

use ArtemSchander\L5Modular\ModuleServiceProvider;

class ModuleListCommandTest extends \Orchestra\Testbench\TestCase
{
    /**
     * The name of the module.
     */
    protected $moduleName = 'FooBar';

    protected function getPackageProviders($app)
    {
        return [ModuleServiceProvider::class];
    }

    protected function tearDown(): void
    {
        $this->app['files']->deleteDirectory($this->app['path'].'/Modules');
        parent::tearDown();
    }

    /** @test */
    public function Should_ShowListOfModule()
    {
        $this->artisan('make:module', ['name' => 'Foo'])
            ->assertExitCode(0);

        $this->artisan('make:module', ['name' => 'Bar'])
            ->assertExitCode(0);

        $this->app['config']->set('modules.specific.Bar.enabled', false);

        $this->artisan('module:list')->assertExitCode(0);
    }

    /** @test */
    public function Should_NotShowListOfModule_When_NoModuleExists()
    {
        $this->app['config']->set('modules.specific.Bar.enabled', false);

        $this->artisan('module:list')
            ->assertExitCode(false);
    }
}
