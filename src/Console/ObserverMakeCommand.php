<?php

namespace ArtemSchander\L5Modular\Console;

use ArtemSchander\L5Modular\Traits\MakesComponent;
use Illuminate\Foundation\Console\ObserverMakeCommand as BaseObserverMakeCommand;
use Illuminate\Support\Str;

class ObserverMakeCommand extends BaseObserverMakeCommand
{
    use MakesComponent;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:module:observer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new observer class in a module';

    /**
     * The key of the component to be generated.
     */
    const KEY = 'observers';

    /**
     * The cli info that will be shown on --help.
     */
    const MODULE_OPTION_INFO = 'Generate an observer in a certain module';

    /**
     * Replace the model for the given stub.
     *
     * @param  string  $stub
     * @param  string  $model
     * @return string
     */
    protected function replaceModel($stub, $model)
    {
        $model = str_replace('/', '\\', $model);

        $relativePart = trim(implode('\\', array_map('ucfirst', explode('/', Str::studly($this->getConfiguredFolder('models'))))), '\\');
        $namespaceModel = $this->laravel->getNamespace() . 'Modules\\' . Str::studly($this->option('module')) . '\\' . $relativePart . '\\' . $model;

        if (Str::startsWith($model, '\\')) {
            $stub = str_replace('NamespacedDummyModel', trim($model, '\\'), $stub);
        } else {
            $stub = str_replace('NamespacedDummyModel', $namespaceModel, $stub);
        }

        $stub = str_replace(
            "use {$namespaceModel};\nuse {$namespaceModel};",
            "use {$namespaceModel};",
            $stub
        );

        $model = class_basename(trim($model, '\\'));

        $search = [ 'DocDummyModel', 'DummyModel', 'dummyModel' ];
        $replace = [ Str::snake($model, ' '), $model, Str::camel($model) ];

        return str_replace($search, $replace, $stub);
    }
}
