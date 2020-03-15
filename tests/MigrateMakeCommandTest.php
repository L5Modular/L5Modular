<?php

namespace ArtemSchander\L5Modular\Tests\Commands;

use ArtemSchander\L5Modular\Tests\MakeCommandTestCase;

class MigrateMakeCommandTest extends MakeCommandTestCase
{
    private $command = 'make:module:migration';

    private $componentName = 'FooMigration';

    private $configStructureKey = 'migrations';

    /** @test */
    public function Should_NotGenerate_When_ModuleDontExists()
    {
        $this->artisan($this->command, [
            'name' => $this->componentName,
            '--module' => $this->moduleName
        ])->assertExitCode(false);
    }
    /** @test */
    public function Should_Generate_When_ModuleExists()
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
    public function Should_AskForModule_When_NoModuleGiven()
    {
        $this->artisan('make:module', ['name' => $this->moduleName])
            ->assertExitCode(0);

        $this->artisan($this->command, ['name' => $this->componentName])
            ->expectsQuestion('In what module would you like to generate?', $this->moduleName)
            ->assertExitCode(0);

        $this->assertDirectoryExists($this->modulePath . '/' . $this->getConfiguredFolder($this->configStructureKey));
    }

    /** @test */
    public function Should_GenerateInGivenPath_When_PathOptionGiven()
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
