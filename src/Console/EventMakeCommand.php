<?php

namespace ArtemSchander\L5Modular\Console;

use ArtemSchander\L5Modular\Traits\HasModuleOption;
use Illuminate\Foundation\Console\EventMakeCommand as BaseEventMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class EventMakeCommand extends BaseEventMakeCommand
{
    use HasModuleOption;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:module:event';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new event class in a module';

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
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Modules\\'.Str::studly($this->module).'\Events';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        $options = parent::getOptions();

        $options[] = ['module', null, InputOption::VALUE_OPTIONAL, 'Generate an event in a certain module'];

        return $options;
    }
}
