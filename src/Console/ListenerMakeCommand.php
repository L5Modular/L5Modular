<?php

namespace ArtemSchander\L5Modular\Console;

use ArtemSchander\L5Modular\Traits\MakesComponent;
use Illuminate\Foundation\Console\ListenerMakeCommand as BaseListenerMakeCommand;
use Illuminate\Support\Str;

class ListenerMakeCommand extends BaseListenerMakeCommand
{
    use MakesComponent;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:module:listener';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new listener class in a module';

    /**
     * The key of the component to be generated.
     *
     * @var string
     */
    const KEY = 'listeners';

    /**
     * The cli info that will be shown on --help.
     */
    const MODULE_OPTION_INFO = 'Generate a listener in a certain module';

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $event = $this->option('event');

        if (! Str::startsWith($event, [ $this->laravel->getNamespace(), 'Illuminate', '\\' ])) {
            $relativePart = trim(implode('\\', array_map('ucfirst', explode('/', Str::studly($this->getConfiguredFolder('events'))))), '\\');
            $event = $this->laravel->getNamespace() . 'Modules\\' . Str::studly($this->option('module')) . '\\' . $relativePart . '\\' . $event;
        }

        $stub = $this->files->get($this->getStub());
        $stub = $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);

        return str_replace([ 'DummyFullEvent', 'DummyEvent' ], [ trim($event, '\\'), class_basename($event) ], $stub);
    }
}
