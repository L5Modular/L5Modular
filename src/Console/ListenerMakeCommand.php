<?php

namespace ArtemSchander\L5Modular\Console;

use Illuminate\Foundation\Console\ListenerMakeCommand as BaseListenerMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ListenerMakeCommand extends BaseListenerMakeCommand
{
    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle()
    {
        $name = $this->qualifyClass($this->getNameInput());
        $path = $this->getPath($name);

        if($this->hasOption('module') && !$this->files->isDirectory(dirname($path, 2))){
            $this->error('Module doesn\'t exist.');
            
            return false;
        }

        parent::handle();
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $event = $this->option('event');

        if (! Str::startsWith($event, [
            $this->laravel->getNamespace(),
            'Illuminate',
            '\\',
        ])) {
            if ($this->hasOption('module')) {
                $event = $this->laravel->getNamespace().'\Modules\\'.Str::studly($this->option('module')).'Events\\'.$event;
            } else {
                $event = $this->laravel->getNamespace().'Events\\'.$event;
            }
        }

        $stub = str_replace(
            'DummyEvent', class_basename($event), parent::buildClass($name)
        );

        return str_replace(
            'DummyFullEvent', trim($event, '\\'), $stub
        );
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        if ($this->hasOption('module')) {
            return $rootNamespace.'\Modules\\'.Str::studly($this->option('module')).'\Listeners';
        } else {
            return parent::getDefaultNamespace($rootNamespace);   
        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        $options = parent::getOptions();

        $options[] = ['module', null, InputOption::VALUE_OPTIONAL, 'Generate a listener in a certain module'];

        return $options;
    }
}
