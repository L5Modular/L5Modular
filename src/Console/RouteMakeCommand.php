<?php

namespace ArtemSchander\L5Modular\Console;

use ArtemSchander\L5Modular\Traits\MakesComponent;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class RouteMakeCommand extends GeneratorCommand
{
    use MakesComponent;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:module:route';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new route file in a module';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Route';

    /**
     * Current route type to be generated.
     *
     * @var string
     */
    protected $stub = 'simple';

    /**
     * The key of the component to be generated.
     */
    const KEY = 'routes';

    /**
     * The cli info that will be shown on --help.
     */
    const MODULE_OPTION_INFO = 'Generate a route file in a certain module';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->initModuleOption();
        if ($this->module) {

            // generate all requested route types
            // or at least the simple route

            $this->generateSimpleRoute();
            $this->generateWebRoute();
            $this->generateApiRoute();
        }
    }

    /**
     * Generate the simple route file
     *
     * @return void
     */
    protected function generateSimpleRoute()
    {
        if ((! $this->option('web') && ! $this->option('api')) || $this->option('simple')) {
            parent::handle();
        }
    }

    /**
     * Generate the web route file
     *
     * @return void
     */
    protected function generateWebRoute()
    {
        if ($this->option('web')) {
            $this->stub = 'web';
            parent::handle();
        }
    }

    /**
     * Generate the api route file
     *
     * @return void
     */
    protected function generateApiRoute()
    {
        if ($this->option('api')) {
            $this->stub = 'api';
            parent::handle();
        }
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $search = [ 'DummyModuleName', 'DummyTitle' ];
        $replace = [ $this->module, Str::kebab($this->module) ];
        return str_replace($search, $replace, parent::buildClass($name));
    }

    /**
     * This generator does not accept a name input
     * therefor this method returns a hardcoded value
     *
     * @return string
     */
    protected function getNameInput()
    {
        $name = 'routes';

        if ($this->stub !== 'simple') {
            $name = $this->stub;
        }

        return $name;
    }

    /**
     * Get the stub file for the generator.
     *
     * @codeCoverageIgnore
     * @return string
     */
    protected function getStub()
    {
        return __DIR__."/stubs/routes/{$this->stub}.stub";
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        $options = parent::getOptions();
        $options[] = ['simple', null, InputOption::VALUE_NONE, 'Generate a simple routes.php file'];
        $options[] = ['web', null, InputOption::VALUE_NONE, 'Generate a web route file'];
        $options[] = ['api', null, InputOption::VALUE_NONE, 'Generate an api route file'];
        $options[] = ['module', null, InputOption::VALUE_OPTIONAL, self::MODULE_OPTION_INFO];
        return $options;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }
}
