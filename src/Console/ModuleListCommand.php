<?php 

namespace ArtemSchander\L5Modular\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ModuleListCommand extends Command {
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
            return $this->error("Your application doesn't have any modules");
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

        if (is_dir(app_path().'/Modules/')) {
            $modules = array_map('class_basename', $this->files->directories(app_path().'/Modules/'));
            $enabled_modules = config("modules.enable") ?: $modules;

            return collect($modules)->map(function ($module) use ($enabled_modules) {
                return ['Module' => $module, 'Status' => in_array($module, $enabled_modules) ? 'Enabled' : 'Disabled'];
            })->sortBy('Module')->values()->toArray();
        }else{
            return null;
        }
    }
}