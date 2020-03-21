<?php

namespace ArtemSchander\L5Modular\Console;

use ArtemSchander\L5Modular\Traits\MakesComponent;
use ArtemSchander\L5Modular\Traits\ReplacesRelatedDataInStub;
use Illuminate\Database\Console\Factories\FactoryMakeCommand as BaseFactoryCommand;

class FactoryMakeCommand extends BaseFactoryCommand
{
    use MakesComponent;
    use ReplacesRelatedDataInStub;

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
     */
    const KEY = 'factories';

    /**
     * The cli info that will be shown on --help.
     */
    const MODULE_OPTION_INFO = 'Generate a factory in a certain module';

    /**
     * The key of the related component.
     * Will be used in ReplacesRelatedDataInStub trait
     */
    const RELATED_COMPONENT = 'model';

    /**
     * The class name beginnings of unrelated components.
     * Will be used in ReplacesRelatedDataInStub trait
     */
    const UNRELATED_COMPONENT_BEGINNINGS = [ '\\' ];

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
