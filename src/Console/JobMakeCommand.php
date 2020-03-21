<?php

namespace ArtemSchander\L5Modular\Console;

use ArtemSchander\L5Modular\Traits\MakesComponent;
use Illuminate\Foundation\Console\JobMakeCommand as BaseJobMakeCommand;

class JobMakeCommand extends BaseJobMakeCommand
{
    use MakesComponent;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:module:job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new job class in a module';

    /**
     * The key of the component to be generated.
     */
    const KEY = 'jobs';

    /**
     * The cli info that will be shown on --help.
     */
    const MODULE_OPTION_INFO = 'Generate a job in a certain module';

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
