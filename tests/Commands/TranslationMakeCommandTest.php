<?php

namespace ArtemSchander\L5Modular\Tests\Commands;

use ArtemSchander\L5Modular\Tests\MakeCommandTestCase;

class TranslationMakeCommandTest extends MakeCommandTestCase
{
    private $command = 'make:module:translation';

    private $languageCode = 'de';
    private $fileName = 'test';

    private $configStructureKey = 'translations';

    /** @test */
    public function should_not_generate_when_module_dont_exists()
    {
        $this->artisan($this->command, [
            'name' => $this->fileName,
            '--language' => $this->languageCode,
            '--module' => $this->moduleName
        ])->assertExitCode(false);
    }

    /** @test */
    public function should_generate_when_module_exists()
    {
        $this->artisan('make:module', ['name' => $this->moduleName])
            ->assertExitCode(0);

        $this->artisan($this->command, [
            'name' => $this->fileName,
            '--language' => $this->languageCode,
            '--module' => $this->moduleName
        ]);

        $this->assertFileExists($this->modulePath . '/' . $this->getConfiguredFolder($this->configStructureKey) . '/' . $this->languageCode . '/' . $this->fileName . '.php');
    }

    /** @test */
    public function should_ask_for_module_when_no_module_given()
    {
        $this->artisan('make:module', ['name' => $this->moduleName])
            ->assertExitCode(0);

        $this->artisan($this->command, [
            'name' => $this->fileName,
            '--language' => $this->languageCode
        ])
            ->expectsQuestion('In what module would you like to generate?', $this->moduleName)
            ->assertExitCode(0);

        $this->assertFileExists($this->modulePath . '/' . $this->getConfiguredFolder($this->configStructureKey) . '/' . $this->languageCode . '/' . $this->fileName . '.php');
    }

    /** @test */
    public function should_ask_for_language_when_no_language_given()
    {
        $this->artisan('make:module', ['name' => $this->moduleName])
            ->assertExitCode(0);

        $this->artisan($this->command, [
            'name' => $this->fileName,
            '--module' => $this->moduleName
        ])
            ->expectsQuestion('In which language would you like to generate the translation?', $this->languageCode)
            ->assertExitCode(0);

        $this->assertFileExists($this->modulePath . '/' . $this->getConfiguredFolder($this->configStructureKey) . '/' . $this->languageCode . '/' . $this->fileName . '.php');
    }
}
