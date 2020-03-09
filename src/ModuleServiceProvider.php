<?php

namespace ArtemSchander\L5Modular;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        Console\ModuleListCommand::class,
        Console\ModuleMakeCommand::class,
        Console\ControllerMakeCommand::class,
        Console\EventMakeCommand::class,
        Console\JobMakeCommand::class,
        Console\ListenerMakeCommand::class,
        Console\MailMakeCommand::class,
        Console\ModelMakeCommand::class,
        Console\NotificationMakeCommand::class,
        Console\ObserverMakeCommand::class,
        Console\RequestMakeCommand::class,
        Console\ResourceMakeCommand::class,
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (is_dir(app_path().'/Modules/')) {
            $modules = config("modules.enable") ?: array_map('class_basename', $this->app['files']->directories(app_path().'/Modules/'));
            foreach ($modules as $module) {
                // Allow routes to be cached
                if (!$this->app->routesAreCached()) {
                    $route_files = [
                        app_path() . '/Modules/' . $module . '/routes.php',
                        app_path() . '/Modules/' . $module . '/routes/web.php',
                        app_path() . '/Modules/' . $module . '/routes/api.php',
                    ];
                    foreach ($route_files as $route_file) {
                        if ($this->app['files']->exists($route_file)) {
                            include $route_file;
                        }
                    }
                }
                $helper = app_path().'/Modules/'.$module.'/helper.php';
                $views  = app_path().'/Modules/'.$module.'/Views';
                $trans  = app_path().'/Modules/'.$module.'/Translations';

                if ($this->app['files']->exists($helper)) {
                    include_once $helper;
                }
                if ($this->app['files']->isDirectory($views)) {
                    $this->loadViewsFrom($views, $module);
                }
                if ($this->app['files']->isDirectory($trans)) {
                    $this->loadTranslationsFrom($trans, $module);
                }
            }
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands($this->commands);
    }
    
    /**
     * Register the given commands.
     *
     * @param  array  $commands
     * @return void
     */
    protected function registerCommands(array $commands)
    {
        $this->commands(array_values($commands));
    }
}
