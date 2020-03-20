<?php

namespace ArtemSchander\L5Modular\Console;

use ArtemSchander\L5Modular\Traits\MakesComponent;
use Illuminate\Database\Console\Seeds\SeederMakeCommand as BaseSeederMakeCommand;

class SeederMakeCommand extends BaseSeederMakeCommand
{
    use MakesComponent;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:module:seeder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new seeder class in a module';

    /**
     * The key of the component to be generated.
     */
    const KEY = 'seeds';

    /**
     * The cli info that will be shown on --help.
     */
    const MODULE_OPTION_INFO = 'Generate a seeder in a certain module';

    /**
     * Get the full namespace for a given class, without the class name.
     *
     * @param  string  $name
     * @return string
     */
    protected function getNamespace($name)
    {
        $rootNamespace = trim($this->rootNamespace(), '\\');
        return $this->getDefaultNamespace($rootNamespace);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/database/seeder.stub';
    }
}
