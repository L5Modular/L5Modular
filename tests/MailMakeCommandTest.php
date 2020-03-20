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
    public function Should_GenerateWithMarkdown_When_MarkdownGiven()
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
    public function Should_GenerateWithMarkdown_When_MarkdownGivenAndViewDirectoryMissing()
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
