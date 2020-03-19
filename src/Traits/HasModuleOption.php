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
     *
     */
    private function initModuleOption()
    {
        if (!$this->module = $this->option('module')) {
            $this->module = $this->ask('In what module would you like to generate?');
        }

        $this->line(app_path('Modules/'.$this->module));

        if (!$this->files->isDirectory(app_path('Modules/'.$this->module))) {
            $this->error('Module doesn\'t exist.');

            $this->module = false;
        }
    }
}
