<?php

namespace ArtemSchander\L5Modular\Traits;

trait ConfiguresFolder
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
}
