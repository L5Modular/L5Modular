<?php

namespace ArtemSchander\L5Modular\Console;

use ArtemSchander\L5Modular\Traits\MakesComponent;
use Illuminate\Console\GeneratorCommand;

class ConfigMakeCommand extends GeneratorCommand
{
    use MakesComponent;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:module:config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new config file in a module';

    /**
     * The type of component being generated.
     *
     * @var string
     */
    protected $type = 'Config file';

    /**
     * The cli info that will be shown on --help.
     */
    const MODULE_OPTION_INFO = 'Generate a config file in a certain module';

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
     * This generator does not accept a name input
     * therefor this method returns a hardcoded value
     *
     * @return string
     */
    protected function getNameInput()
    {
        return 'config';
    }

    /**
     * Get the configured path for the current component.
     *
     * @param  string  $key
     * @return string
     */
    protected function getConfiguredFolder($key = null)
    {
        return '';
    }

    /**
     * Get the stub file for the generator.
     *
     * @codeCoverageIgnore
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/config.stub';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }
}
