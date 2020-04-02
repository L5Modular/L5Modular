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

        $this->assertFileExists($this->modulePath . '/' . $this->getConfiguredFolder($this->configStructureKey) . '/' . $this->componentName . '.php');
    }

    /** @test */
    public function should_ask_for_module_when_no_module_given()
    {
        $this->artisan('make:module', ['name' => $this->moduleName])
            ->assertExitCode(0);

        $this->artisan($this->command, ['name' => $this->componentName])
            ->expectsQuestion('In what module would you like to generate?', $this->moduleName)
            ->assertExitCode(0);

        $this->assertFileExists($this->modulePath . '/' . $this->getConfiguredFolder($this->configStructureKey) . '/' . $this->componentName . '.php');
    }

    /** @test */
    public function should_generate_with_model_when_model_option_given()
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
    public function should_not_generate_with_model_when_model_option_has_invalid_name()
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
