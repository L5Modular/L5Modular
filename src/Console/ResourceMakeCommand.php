<?php

namespace ArtemSchander\L5Modular\Console;

use ArtemSchander\L5Modular\Traits\MakesComponent;
use Illuminate\Foundation\Console\ResourceMakeCommand as BaseResourceMakeCommand;

class ResourceMakeCommand extends BaseResourceMakeCommand
{
    use MakesComponent;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:module:resource';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new resource class in a module';

    /**
     * The key of the component to be generated.
     */
    const KEY = 'resources';

    /**
     * The cli info that will be shown on --help.
     */
    const MODULE_OPTION_INFO = 'Generate a resource in a certain module';

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
