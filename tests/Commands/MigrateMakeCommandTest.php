<?php

namespace ArtemSchander\L5Modular\Tests\Commands;

use ArtemSchander\L5Modular\Tests\MakeCommandTestCase;

class MigrateMakeCommandTest extends MakeCommandTestCase
{
    private $command = 'make:module:migration';

    private $componentName = 'FooMigration';

    private $configStructureKey = 'migrations';

    /** @test */
    public function should_not_generate_when_module_dont_exists()
    {
        $this->artisan($this->command, [
            'name' => $this->componentName,
            '--module' => $this->moduleName
        ])->assertExitCode(false);
    }
    /** @test */
    public function should_generate_when_module_exists()
    {
        $this->artisan('make:module', ['name' => $this->moduleName])
            ->assertExitCode(0);

        $this->artisan($this->command, [
            'name' => $this->componentName,
            '--module' => $this->moduleName
        ])->assertExitCode(0);

        $this->assertDirectoryExists($this->modulePath . '/' . $this->getConfiguredFolder($this->configStructureKey));
    }

    /** @test */
    public function should_ask_for_module_when_no_module_given()
    {
        $this->artisan('make:module', ['name' => $this->moduleName])
            ->assertExitCode(0);

        $this->artisan($this->command, ['name' => $this->componentName])
            ->expectsQuestion('In what module would you like to generate?', $this->moduleName)
            ->assertExitCode(0);

        $this->assertDirectoryExists($this->modulePath . '/' . $this->getConfiguredFolder($this->configStructureKey));
    }

    /** @test */
    public function should_generate_in_given_path_when_path_option_given()
    {
        $this->artisan('make:module', ['name' => $this->moduleName])
            ->assertExitCode(0);

        $this->artisan($this->command, [
            'name' => $this->componentName,
            '--path' => 'fooBar',
        ])->expectsQuestion('In what module would you like to generate?', $this->moduleName)
            ->assertExitCode(0);

        $this->assertDirectoryExists($this->modulePath . '/' . $this->getConfiguredFolder($this->configStructureKey) . '/fooBar');
    }
}
