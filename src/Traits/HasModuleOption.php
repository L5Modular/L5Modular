<?php

namespace ArtemSchander\L5Modular\Traits;

trait HasModuleOption {
    /**
     * The module where the class will be generated.
     *
     * @var string
     */
    private $module;

    /**
     * 
     */
    private function initModuleOption(){
        if (!$this->module = $this->option('module')){
            $this->module = $this->ask('In what module would you like to generate a '.$this->type.'?');
        }

        $name = $this->qualifyClass($this->getNameInput());
        $path = $this->getPath($name);

        if(!$this->files->isDirectory(dirname($path, 2))) {
            $this->error('Module doesn\'t exist.');
            
            $this->module = false;
        }
    }
}