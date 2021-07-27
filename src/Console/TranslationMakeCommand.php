<?php

namespace ArtemSchander\L5Modular\Console;

use ArtemSchander\L5Modular\Traits\MakesComponent;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class TranslationMakeCommand extends GeneratorCommand
{
    use MakesComponent;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:module:translation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new translation file in a module';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Translation';

    /**
     * The language short code of the translation
     *
     * @var string
     */
    protected $language = 'en';

    /**
     * The key of the component to be generated.
     */
    const KEY = 'translations';

    /**
     * The cli info that will be shown on --help.
     */
    const MODULE_OPTION_INFO = 'Generate a translation file in a certain module';

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        return str_replace('DummyModuleName', $this->module, parent::buildClass($name));
    }

    /**
     * Get the stub file for the generator.
     *
     * @codeCoverageIgnore
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/resources/translation.stub';
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = $this->getNameInput();
        $path = $this->laravel['path'] . '/Modules/' . Str::studly($this->module) . '/' . $this->getConfiguredFolder() .  '/' . $this->language . '/' . $name . '.php';
        return str_replace('//', '/', $path);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The translation file name'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        $options[] = ['module', null, InputOption::VALUE_OPTIONAL, self::MODULE_OPTION_INFO];
        $options[] = ['language', null, InputOption::VALUE_OPTIONAL, 'The language short code of the translation'];
        return $options;
    }

    /**
     * Initialize --module flag
     *
     * @return void
     */
    protected function initModuleOption()
    {
        if (! $this->module = $this->option('module')) {
            $this->module = $this->ask('In what module would you like to generate?');
        }

        if (! $this->language = $this->option('language')) {
            $this->language = $this->ask('In which language would you like to generate the translation?', 'en');
        }

        if (! $this->option('quiet')) {
            $this->line('app/Modules/' . $this->module);
        }

        if (!$this->files->isDirectory(app_path('Modules/' . $this->module))) {
            $this->error('Module doesn\'t exist.');
            $this->module = false;
        }
    }
}
