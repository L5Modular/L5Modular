<?php

namespace ArtemSchander\L5Modular\Console;

use ArtemSchander\L5Modular\Traits\MakesComponent;
use Illuminate\Routing\Console\ControllerMakeCommand as BaseControllerMakeCommand;
use Symfony\Component\Console\Input\InputOption;
use InvalidArgumentException;
use Illuminate\Support\Str;

class ControllerMakeCommand extends BaseControllerMakeCommand
{
    use MakesComponent;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:module:controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new controller class in a module';

    /**
     * The key of the component to be generated.
     */
    const KEY = 'controllers';

    /**
     * The cli info that will be shown on --help.
     */
    const MODULE_OPTION_INFO = 'Generate a controller in a certain module';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('welcome')) {
            $stub = __DIR__.'/stubs/controller.stub';
        } else {
            $stub = parent::getStub();
        }

        return $stub;
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in base namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        return str_replace('DummyModuleName', $this->module, parent::buildClass($name));
    }

    /**
     * Get the fully-qualified model class name.
     *
     * @param  string  $model
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function parseModel($model)
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }

        $model = trim(str_replace('/', '\\', $model), '\\');

        if (! Str::startsWith($model, $rootNamespace = $this->laravel->getNamespace())) {
            $relativePart = trim(implode('\\', array_map('ucfirst', explode('/', Str::studly($this->getConfiguredFolder('models'))))), '\\');
            $model = $rootNamespace . 'Modules\\' . Str::studly($this->module) . '\\' . $relativePart . '\\' . $model;
        }

        return $model;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        $options = parent::getOptions();
        $options[] = ['welcome', 'w', InputOption::VALUE_NONE, 'Generate a controller with a welcome method.'];
        $options[] = ['module', null, InputOption::VALUE_OPTIONAL, self::MODULE_OPTION_INFO];
        return $options;
    }
}
