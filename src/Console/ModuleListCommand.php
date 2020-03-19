<?php

namespace ArtemSchander\L5Modular\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ModuleListCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "List the application's modules";

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new controller creator command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $modules = $this->getModules();

        if (empty($modules)) {
            $this->error('Your application doesn\'t have any modules');

            return false;
        }

        $this->table(['Module', 'Status'], $modules);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function getModules()
    {
        $module_path = $this->laravel['path'] . '/Modules';

        if ($this->files->exists($module_path) && !empty($this->files->directories($module_path))) {
            $modules = array_map('class_basename', $this->files->directories($module_path));

            return collect($modules)->map(function ($module) {
                $module_enabled = config('modules.specific.' . $module . '.enabled', true);

                return ['Module' => $module, 'Status' => $module_enabled ? 'Enabled' : 'Disabled'];
            })->sortBy('Module')->values()->toArray();
        } else {
            return null;
        }
    }
}
