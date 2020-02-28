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
        
        $this->registerModuleMakeCommand();

        $this->extendControllerMakeCommand();
        
        $this->extendEventMakeCommand();
        
        $this->extendJobMakeCommand();
        
        $this->extendListenerMakeCommand();
        
        $this->extendMailMakeCommand();
        
        $this->extendModelMakeCommand();
        
        $this->extendNotificationMakeCommand();
        
        $this->extendObserverMakeCommand();
        
        $this->extendRequestMakeCommand();
        
        $this->extendResourceMakeCommand();
    }

    /**
     * Register the "make:module" console command.
     *
     * @return void
     */
    protected function registerModuleMakeCommand()
    {
        $this->commands('modules.make');

        $bind_method = method_exists($this->app, 'bindShared') ? 'bindShared' : 'singleton';

        $this->app->{$bind_method}('modules.make', function ($app) {
            return new Console\ModuleMakeCommand($this->files);
        });
    }
    
    /**
     * Extend the "make:controller" console command.
     *
     * @return void
     */
    protected function extendControllerMakeCommand()
    {
        $this->app->extend('command.controller.make', function ($app) {
            return new Console\ControllerMakeCommand($this->files);
        });
    }
    
    /**
     * Extend the "make:event" console command.
     *
     * @return void
     */
    protected function extendEventMakeCommand()
    {
        $this->app->extend('command.event.make', function ($app) {
            return new Console\EventMakeCommand($this->files);
        });
    }
    
    /**
     * Extend the "make:job" console command.
     *
     * @return void
     */
    protected function extendJobMakeCommand()
    {
        $this->app->extend('command.job.make', function ($app) {
            return new Console\JobMakeCommand($this->files);
        });
    }
    
    /**
     * Extend the "make:listener" console command.
     *
     * @return void
     */
    protected function extendListenerMakeCommand()
    {
        $this->app->extend('command.listener.make', function ($app) {
            return new Console\ListenerMakeCommand($this->files);
        });
    }
    
    /**
     * Extend the "make:mail" console command.
     *
     * @return void
     */
    protected function extendMailMakeCommand()
    {
        $this->app->extend('command.mail.make', function ($app) {
            return new Console\MailMakeCommand($this->files);
        });
    }
    
    /**
     * Extend the "make:model" console command.
     *
     * @return void
     */
    protected function extendModelMakeCommand()
    {
        $this->app->extend('command.model.make', function ($app) {
            return new Console\ModelMakeCommand($this->files);
        });
    }
    
    /**
     * Extend the "make:notification" console command.
     *
     * @return void
     */
    protected function extendNotificationMakeCommand()
    {
        $this->app->extend('command.notification.make', function ($app) {
            return new Console\NotificationMakeCommand($this->files);
        });
    }
    
    /**
     * Extend the "make:observer" console command.
     *
     * @return void
     */
    protected function extendObserverMakeCommand()
    {
        $this->app->extend('command.observer.make', function ($app) {
            return new Console\ObserverMakeCommand($this->files);
        });
    }

    /**
     * Extend the "make:request" console command.
     *
     * @return void
     */
    protected function extendRequestMakeCommand()
    {
        $this->app->extend('command.request.make', function ($app) {
            return new Console\RequestMakeCommand($this->files);
        });
    }

    /**
     * Extend the "make:resource" console command.
     *
     * @return void
     */
    protected function extendResourceMakeCommand()
    {
        $this->app->extend('command.resource.make', function ($app) {
            return new Console\ResourceMakeCommand($this->files);
        });
    }
}
