<?php

namespace ArtemSchander\L5Modular\Console;

use ArtemSchander\L5Modular\Traits\HasModuleOption;
use Illuminate\Foundation\Console\RequestMakeCommand as BaseRequestMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class RequestMakeCommand extends BaseRequestMakeCommand
{
    use HasModuleOption;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:module:request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new request class in a module';

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
        return $rootNamespace.'\Modules\\'.Str::studly($this->module).'\Requests';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        $options = parent::getOptions();

        $options[] = ['module', null, InputOption::VALUE_OPTIONAL, 'Generate a request in a certain module'];

        return $options;
    }
}
