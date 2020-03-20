<?php

namespace ArtemSchander\L5Modular\Console;

use ArtemSchander\L5Modular\Traits\MakesComponent;
use Illuminate\Database\Console\Factories\FactoryMakeCommand as BaseFactoryCommand;
use Illuminate\Support\Str;

class FactoryMakeCommand extends BaseFactoryCommand
{
    use MakesComponent;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:module:factory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model factory in a module';

    /**
     * The key of the component to be generated.
     *
     * @var string
     */
    const KEY = 'factories';

    /**
     * The cli info that will be shown on --help.
     */
    const MODULE_OPTION_INFO = 'Generate a factory in a certain module';

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $model = $this->option('model');

        if (! Str::startsWith($model, [ $this->laravel->getNamespace(), '\\' ])) {
            $relativePart = trim(implode('\\', array_map('ucfirst', explode('/', Str::studly($this->getConfiguredFolder('models'))))), '\\');
            $model = $this->laravel->getNamespace() . 'Modules\\' . Str::studly($this->option('module')) . '\\' . $relativePart . '\\' . $model;
        }

        $stub = $this->files->get($this->getStub());
        $stub = $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);

        return str_replace([ 'NamespacedDummyModel', 'DummyModel' ], [ trim($model, '\\'), class_basename($model) ], $stub);
    }
}
