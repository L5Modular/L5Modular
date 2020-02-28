<?php

namespace ArtemSchander\L5Modular;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    protected $files;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (is_dir(app_path().'/Modules/')) {
            $modules = config("modules.enable") ?: array_map('class_basename', $this->files->directories(app_path().'/Modules/'));
            foreach ($modules as $module) {
                // Allow routes to be cached
                if (!$this->app->routesAreCached()) {
                    $route_files = [
                        app_path() . '/Modules/' . $module . '/routes.php',
                        app_path() . '/Modules/' . $module . '/routes/web.php',
                        app_path() . '/Modules/' . $module . '/routes/api.php',
                    ];
                    foreach ($route_files as $route_file) {
                        if ($this->files->exists($route_file)) {
                            include $route_file;
                        }
                    }
                }
                $helper = app_path().'/Modules/'.$module.'/helper.php';
                $views  = app_path().'/Modules/'.$module.'/Views';
                $trans  = app_path().'/Modules/'.$module.'/Translations';

                if ($this->files->exists($helper)) {
                    include_once $helper;
                }
                if ($this->files->isDirectory($views)) {
                    $this->loadViewsFrom($views, $module);
                }
                if ($this->files->isDirectory($trans)) {
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
        $this->files = new Filesystem;
        $this->registerMakeCommand();
    }

    /**
     * Register the "make:module" console command.
     *
     * @return Console\ModuleMakeCommand
     */
    protected function registerMakeCommand()
    {
        $this->commands('modules.make');

        $bind_method = method_exists($this->app, 'bindShared') ? 'bindShared' : 'singleton';

        $this->app->{$bind_method}('modules.make', function ($app) {
            return new Console\ModuleMakeCommand($this->files);
        });

        $this->app->extend('command.make:controller', function () {
            return new Console\ControllerMakeCommand($this->files);
        });
        $this->app->extend('command.make:event', function () {
            return new Console\EventMakeCommand($this->files);
        });
        $this->app->extend('command.make:job', function () {
            return new Console\JobMakeCommand($this->files);
        });
        $this->app->extend('command.make:listener', function () {
            return new Console\ListenerMakeCommand($this->files);
        });
        $this->app->extend('command.make:mail', function () {
            return new Console\MailMakeCommand($this->files);
        });
        $this->app->extend('command.make:model', function () {
            return new Console\ModelMakeCommand($this->files);
        });
        $this->app->extend('command.make:notification', function () {
            return new Console\NotificationMakeCommand($this->files);
        });
        $this->app->extend('command.make:observer', function () {
            return new Console\ObserverMakeCommand($this->files);
        });
        $this->app->extend('command.make:request', function () {
            return new Console\RequestMakeCommand($this->files);
        });
        $this->app->extend('command.make:resource', function () {
            return new Console\ResourceMakeCommand($this->files);
        });
    }
}
