<?php

namespace ArtemSchander\L5Modular\Tests\Commands;

use ArtemSchander\L5Modular\Tests\MakeCommandTestCase;
use InvalidArgumentException;

class ControllerMakeCommandTest extends MakeCommandTestCase
{
    private $command = 'make:module:controller';

    private $componentName = 'FooController';

    private $configStructureKey = 'controllers';

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

        $this->assertFileExists($this->modulePath . '/' . $this->getConfiguredFolder($this->configStructureKey) . '/' . $this->componentName . '.php');
    }

    /** @test */
    public function Should_AskForModule_When_NoModuleGiven()
    {
        $this->artisan('make:module', ['name' => $this->moduleName])
            ->assertExitCode(0);

        $this->artisan($this->command, ['name' => $this->componentName])
            ->expectsQuestion('In what module would you like to generate?', $this->moduleName)
            ->assertExitCode(0);

        $this->assertFileExists($this->modulePath . '/' . $this->getConfiguredFolder($this->configStructureKey) . '/' . $this->componentName . '.php');
    }

    /** @test */
    public function Should_GenerateWithModel_When_ModelOptionGiven()
    {
        $this->artisan('make:module', ['name' => $this->moduleName])
            ->assertExitCode(0);

        $this->artisan($this->command, [
            'name' => $this->componentName,
            '--model' => 'Test',
            '--module' => $this->moduleName
        ])
            ->expectsQuestion('A App\Modules\FooBar\Models\Test model does not exist. Do you want to generate it?', 'yes')
            ->assertExitCode(0);

        $this->assertFileExists($this->modulePath . '/' . $this->getConfiguredFolder($this->configStructureKey) . '/' . $this->componentName . '.php');
        $this->assertFileExists($this->modulePath . '/' . $this->getConfiguredFolder('models') . '/Test.php');
    }

    /** @test */
    public function Should_Not_GenerateWithModel_When_ModelOption_Has_InvalidName()
    {
        $this->artisan('make:module', ['name' => $this->moduleName])
            ->assertExitCode(0);

        $this->expectException(InvalidArgumentException::class);

        $this->artisan($this->command, [
            'name' => $this->componentName,
            '--model' => 'Inavalid Name',
            '--module' => $this->moduleName
        ])
            ->assertExitCode(0);

        $this->assertFileNotExists($this->modulePath . '/' . $this->getConfiguredFolder($this->configStructureKey) . '/' . $this->componentName . '.php');
        $this->assertFileNotExists($this->modulePath . '/' . $this->getConfiguredFolder('models') . '/Test.php');
    }
}
