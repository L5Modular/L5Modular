<?php

namespace ArtemSchander\L5Modular\Console;

use ArtemSchander\L5Modular\Traits\ConfiguresFolder;
use ArtemSchander\L5Modular\Traits\HasModuleOption;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Database\Console\Seeds\SeederMakeCommand as BaseSeederMakeCommand;
use Illuminate\Support\Str;

class SeederMakeCommand extends BaseSeederMakeCommand
{
    use ConfiguresFolder, HasModuleOption;

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
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->initModuleOption();

        if (!$this->module) {
            return false;
        }

        parent::handle();

        $this->composer->dumpAutoloads();
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

    /**
     * Get the full namespace for a given class, without the class name.
     *
     * @param  string  $name
     * @return string
     */
    protected function getNamespace($name)
    {
        $relativePart = trim(implode('\\', array_map('ucfirst', explode('/', Str::studly($this->getConfiguredFolder('seeds'))))), '\\');
        return $this->laravel->getNamespace() . 'Modules\\' . Str::studly($this->module) . '\\' . $relativePart;
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        return $this->laravel['path'] . '/Modules/' . Str::studly($this->module) . '/' . $this->getConfiguredFolder('seeds') . '/' . $name . '.php';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        $options = parent::getOptions();

        $options[] = ['module', null, InputOption::VALUE_OPTIONAL, 'Generate a seeder in a certain module'];

        return $options;
    }
}
