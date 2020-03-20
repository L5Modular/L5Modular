<?php

namespace ArtemSchander\L5Modular\Console;

use ArtemSchander\L5Modular\Traits\MakesComponent;
use Illuminate\Routing\Console\ControllerMakeCommand as BaseControllerMakeCommand;
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
     *
     * @var string
     */
    const KEY = 'controllers';

    /**
     * The cli info that will be shown on --help.
     */
    const MODULE_OPTION_INFO = 'Generate a controller in a certain module';

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
}
