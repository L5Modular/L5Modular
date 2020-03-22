<?php

namespace ArtemSchander\L5Modular\Console;

use ArtemSchander\L5Modular\Traits\MakesComponent;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Console\GeneratorCommand;

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
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The language short code of the translation'],
        ];
    }
}
