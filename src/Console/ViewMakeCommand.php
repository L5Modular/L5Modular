<?php

namespace ArtemSchander\L5Modular\Console;

use ArtemSchander\L5Modular\Traits\MakesComponent;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Console\GeneratorCommand;

class ViewMakeCommand extends GeneratorCommand
{
    use MakesComponent;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:module:view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new blade view file in a module';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'View';

    /**
     * The key of the component to be generated.
     */
    const KEY = 'views';

    /**
     * The cli info that will be shown on --help.
     */
    const MODULE_OPTION_INFO = 'Generate a view file in a certain module';

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
     * Get the desired name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return str_replace('.blade', '', parent::getNameInput()) . '.blade';
    }

    /**
     * Get the stub file for the generator.
     *
     * @codeCoverageIgnore
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/resources/view.stub';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name for the blade view'],
        ];
    }
}
