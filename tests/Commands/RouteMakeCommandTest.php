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
    public function should_not_generate_when_module_dont_exists()
    {
        $this->artisan($this->command, [ '--module' => $this->moduleName ])->assertExitCode(false);
    }

    /** @test */
    public function should_generate_when_module_exists()
    {
        $this->artisan('make:module', ['name' => $this->moduleName])
            ->assertExitCode(0);

        $this->artisan($this->command, [
            '--module' => $this->moduleName
        ])->assertExitCode(0);

        $this->assertFileExists($this->modulePath . '/' . $this->getConfiguredFolder($this->configStructureKey) . '/routes.php');
    }

    /** @test */
    public function should_ask_for_module_when_no_module_given()
    {
        $this->artisan('make:module', ['name' => $this->moduleName])
            ->assertExitCode(0);

        $this->artisan($this->command)
            ->expectsQuestion('In what module would you like to generate?', $this->moduleName)
            ->assertExitCode(0);

        $this->assertFileExists($this->modulePath . '/' . $this->getConfiguredFolder($this->configStructureKey) . '/routes.php');
    }

    /** @test */
    public function should_generate_the_api_route_file()
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
    public function should_generate_the_web_route_file()
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
    public function should_generate_api_and_web_route_files()
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
