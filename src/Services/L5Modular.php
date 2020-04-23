<?php

namespace ArtemSchander\L5Modular\Services;

use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;

class L5Modular
{

    protected $config;
    protected $files;

    /**
     * Bootstrap the application services.
     *
     * @param  \Illuminate\Config\Repository  $config
     * @param  \Illuminate\Filesystem\Filesystem  $files
     *
     * @return void
     */
    public function __construct(Repository $config, Filesystem $files)
    {
        $this->config = $config;
        $this->files = $files;
    }

    /**
     * Check if a module exists
     *
     * @param string $module
     *
     * @return boolean
     */
    public function exists(string $module)
    {
        $path = app_path("Modules/{$module}");
        return $this->files->isDirectory($path);
    }

    /**
     * Check if a module is enabled
     *
     * @param string $module
     *
     * @return boolean
     */
    public function enabled(string $module)
    {
        $key = $this->getConfigStatusPath($module);
        return $this->exists($module) && $this->config->get($key, true) && $this->config->get("{$module}.enabled", true);
    }

    /**
     * Check if a module is not enabled
     *
     * @param string $module
     *
     * @return boolean
     */
    public function disabled(string $module)
    {
        return ! $this->enabled($module);
    }

    /**
     * enable module
     *
     * @param string $module
     *
     * @return void
     */
    public function enable(string $module)
    {
        $this->setStatus($module, true);
    }

    /**
     * disable module
     *
     * @param string $module
     *
     * @return void
     */
    public function disable(string $module)
    {
        $this->setStatus($module, false);
    }

    /**
     * change module status
     *
     * @return void
     */
    protected function setStatus(string $module, bool $status)
    {
        if ($this->exists($module)) {
            $key = $this->getConfigStatusPath($module);
            $this->config->set($key, $status);
            $this->config->set("{$module}.enabled", $status);
        }
    }

    /**
     * get the config path to the status setting of given module
     *
     * @return void
     */
    protected function getConfigStatusPath(string $module)
    {
        return "modules.specific.{$module}.enabled";
    }
}
