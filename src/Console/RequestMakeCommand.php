<?php

namespace ArtemSchander\L5Modular\Console;

use ArtemSchander\L5Modular\Traits\MakesComponent;
use Illuminate\Foundation\Console\RequestMakeCommand as BaseRequestMakeCommand;

class RequestMakeCommand extends BaseRequestMakeCommand
{
    use MakesComponent;

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
     * The key of the component to be generated.
     */
    const KEY = 'requests';

    /**
     * The cli info that will be shown on --help.
     */
    const MODULE_OPTION_INFO = 'Generate a request in a certain module';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->initModuleOption();
        return $this->module ? parent::handle() : false;
    }
}
