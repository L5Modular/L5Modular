<?php

namespace ArtemSchander\L5Modular\Console;

use ArtemSchander\L5Modular\Traits\HasModuleOption;
use Illuminate\Foundation\Console\ObserverMakeCommand as BaseObserverMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ObserverMakeCommand extends BaseObserverMakeCommand
{
    use HasModuleOption;

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
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle()
    {
        $this->initModuleOption();

        return $this->module ? parent::handle() : false;
    }
    
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

        $namespaceModel = $this->laravel->getNamespace().'\Modules\\'.Str::studly($this->option('module')).'\\'.$model;

        if (Str::startsWith($model, '\\')) {
            $stub = str_replace('NamespacedDummyModel', trim($model, '\\'), $stub);
        } else {
            $stub = str_replace('NamespacedDummyModel', $namespaceModel, $stub);
        }

        $stub = str_replace(
            "use {$namespaceModel};\nuse {$namespaceModel};", "use {$namespaceModel};", $stub
        );

        $model = class_basename(trim($model, '\\'));

        $stub = str_replace('DocDummyModel', Str::snake($model, ' '), $stub);

        $stub = str_replace('DummyModel', $model, $stub);

        return str_replace('dummyModel', Str::camel($model), $stub);
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Modules\\'.Str::studly($this->module).'\Observers';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        $options = parent::getOptions();

        $options[] = ['module', null, InputOption::VALUE_OPTIONAL, 'Generate an observer in a certain module'];

        return $options;
    }
}
