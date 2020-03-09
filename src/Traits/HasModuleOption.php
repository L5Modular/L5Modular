<?php

namespace ArtemSchander\L5Modular\Traits;

trait HasModuleOption
{
    /**
     * The module where the class will be generated.
     *
     * @var string
     */
    protected $module;

    /**
     * Get the configured path for the asked component.
     *
     * @param  string  $component
     * @return string
     */
    protected function getConfiguredFolder(string $component)
    {
        return config("modules.specific.{$this->module}.structure.{$component}", config("modules.default.structure.{$component}"));
    }

    /**
     * 
     */
    private function initModuleOption()
    {
        if (!$this->module = $this->option('module')) {
            $this->module = $this->ask('In what module would you like to generate a '.$this->type.'?');
        }

        $name = $this->qualifyClass($this->getNameInput());
        $path = $this->getPath($name);

        if (!$this->files->isDirectory(dirname($path, 2))) {
            $this->error('Module doesn\'t exist.');
            
            $this->module = false;
        }
    }
}