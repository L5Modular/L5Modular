<?php

namespace ArtemSchander\L5Modular\Tests\Commands;

use ArtemSchander\L5Modular\Tests\MakeCommandTestCase;

class RouteMakeCommandTest extends MakeCommandTestCase
{
    private $command = 'make:module:route';

    private $configStructureKey = 'routes';

    public function setUp(): void
    {
        parent::setUp();
        $this->app['config']->set('modules.generate.routes', false);
    }

    /** @test */
    public function Should_NotGenerate_When_ModuleDontExists()
    {
        $this->artisan($this->command, [ '--module' => $this->moduleName ])->assertExitCode(false);
    }

    /** @test */
    public function Should_Generate_When_ModuleExists()
    {
        $this->artisan('make:module', ['name' => $this->moduleName])
            ->assertExitCode(0);

        $this->artisan($this->command, [
            '--module' => $this->moduleName
        ])->assertExitCode(0);

        $this->assertFileExists($this->modulePath . '/' . $this->getConfiguredFolder($this->configStructureKey) . '/routes.php');
    }

    /** @test */
    public function Should_AskForModule_When_NoModuleGiven()
    {
        $this->artisan('make:module', ['name' => $this->moduleName])
            ->assertExitCode(0);

        $this->artisan($this->command)
            ->expectsQuestion('In what module would you like to generate?', $this->moduleName)
            ->assertExitCode(0);

        $this->assertFileExists($this->modulePath . '/' . $this->getConfiguredFolder($this->configStructureKey) . '/routes.php');
    }

    /** @test */
    public function Should_Generate_The_Api_Route_File()
    {
        $this->artisan('make:module', ['name' => $this->moduleName])
            ->assertExitCode(0);

        $this->artisan($this->command, [
            '--module' => $this->moduleName,
            '--api' => true,
        ])
            ->assertExitCode(0);

        $this->assertFileNotExists($this->modulePath . '/' . $this->getConfiguredFolder($this->configStructureKey) . '/routes.php');
        $this->assertFileNotExists($this->modulePath . '/' . $this->getConfiguredFolder($this->configStructureKey) . '/web.php');
        $this->assertFileExists($this->modulePath . '/' . $this->getConfiguredFolder($this->configStructureKey) . '/api.php');
    }

    /** @test */
    public function Should_Generate_The_Web_Route_File()
    {
        $this->artisan('make:module', ['name' => $this->moduleName])
            ->assertExitCode(0);

        $this->artisan($this->command, [
            '--module' => $this->moduleName,
            '--web' => true,
        ])
            ->assertExitCode(0);

        $this->assertFileNotExists($this->modulePath . '/' . $this->getConfiguredFolder($this->configStructureKey) . '/routes.php');
        $this->assertFileExists($this->modulePath . '/' . $this->getConfiguredFolder($this->configStructureKey) . '/web.php');
        $this->assertFileNotExists($this->modulePath . '/' . $this->getConfiguredFolder($this->configStructureKey) . '/api.php');
    }

    /** @test */
    public function Should_Generate_Api_And_Web_Route_Files()
    {
        $this->artisan('make:module', ['name' => $this->moduleName])
            ->assertExitCode(0);

        $this->artisan($this->command, [
            '--module' => $this->moduleName,
            '--api' => true,
            '--web' => true,
        ])
            ->assertExitCode(0);

        $this->assertFileNotExists($this->modulePath . '/' . $this->getConfiguredFolder($this->configStructureKey) . '/routes.php');
        $this->assertFileExists($this->modulePath . '/' . $this->getConfiguredFolder($this->configStructureKey) . '/web.php');
        $this->assertFileExists($this->modulePath . '/' . $this->getConfiguredFolder($this->configStructureKey) . '/api.php');
    }
}
