<?php

namespace ArtemSchander\L5Modular\Traits;

use Symfony\Component\Console\Input\InputOption;
use Illuminate\Support\Str;

trait MakesComponent
{
    /**
     * The module where the class will be generated.
     *
     * @var string
     */
    protected $module;

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

    /**
     * Get the configured path for the current component.
     *
     * @param  string  $key
     * @return string
     */
    protected function getConfiguredFolder($key = null)
    {
        if (! $key) $key = self::KEY;
        return config("{$this->module}.structure.{$key}", config("modules.default.structure.{$key}"));
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        $relativePart = trim(implode('\\', array_map('ucfirst', explode('/', Str::studly($this->getConfiguredFolder())))), '\\');
        return $rootNamespace . '\Modules\\' . Str::studly($this->module) . '\\' . $relativePart;
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = $this->getNameInput();
        $path = $this->laravel['path'] . '/Modules/' . Str::studly($this->module) . '/' . $this->getConfiguredFolder() . '/' . $name . '.php';
        return str_replace('//', '/', $path);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        $options = parent::getOptions();
        $options[] = ['module', null, InputOption::VALUE_OPTIONAL, self::MODULE_OPTION_INFO];
        return $options;
    }

    /**
     * Initialize --module flag
     *
     * @return void
     */
    protected function initModuleOption()
    {
        if (! $this->module = $this->option('module')) {
            $this->module = $this->ask('In what module would you like to generate?');
        }

        if (! $this->option('quiet')) {
            $this->line('app/Modules/' . $this->module);
        }

        if (!$this->files->isDirectory(app_path('Modules/' . $this->module))) {
            $this->error('Module doesn\'t exist.');
            $this->module = false;
        }
    }
}
