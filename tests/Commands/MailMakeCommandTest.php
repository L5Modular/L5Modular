<?php

namespace ArtemSchander\L5Modular\Tests\Commands;

use ArtemSchander\L5Modular\Tests\MakeCommandTestCase;

class MailMakeCommandTest extends MakeCommandTestCase
{
    private $command = 'make:module:mail';

    private $componentName = 'FooMail';

    private $markdownName = 'foo-mail-markdown';

    private $configStructureKey = 'mails';

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
    public function should_generate_with_markdown_when_markdown_given()
    {
        $this->artisan('make:module', ['name' => $this->moduleName])
            ->assertExitCode(0);

        $this->artisan($this->command, [
            'name' => $this->componentName,
            '--module' => $this->moduleName,
            '--markdown' => $this->markdownName,
        ])->assertExitCode(0);

        $this->assertFileExists($this->modulePath . '/' . $this->getConfiguredFolder($this->configStructureKey) . '/' . $this->componentName . '.php');
        $this->assertFileExists($this->modulePath . '/' . $this->getConfiguredFolder('views') . '/' . $this->markdownName . '.blade.php');
    }

    /** @test */
    public function should_generate_with_markdown_when_markdown_given_and_view_directory_missing()
    {
        $this->app['config']->set('modules.generate.view', false);

        $this->artisan('make:module', ['name' => $this->moduleName])
            ->assertExitCode(0);

        $this->artisan($this->command, [
            'name' => $this->componentName,
            '--module' => $this->moduleName,
            '--markdown' => $this->markdownName,
        ])->assertExitCode(0);

        $this->assertFileExists($this->modulePath . '/' . $this->getConfiguredFolder($this->configStructureKey) . '/' . $this->componentName . '.php');
        $this->assertFileExists($this->modulePath . '/' . $this->getConfiguredFolder('views') . '/' . $this->markdownName . '.blade.php');
    }
}
