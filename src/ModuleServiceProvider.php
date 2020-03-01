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
        // foreach (array_keys($commands) as $command) {
        //     call_user_func_array([$this, "register{$command}Command"], []);
        // }

        $this->commands(array_values($commands));
    }
    
    /**
     * Register the "make:module" console command.
     *
     * @return void
     */
    protected function registerModuleListCommand()
    {
        $this->app->singleton('modules.list', function ($app) {
            return new Console\ModuleListCommand($app['files']);
        });
    }

    /**
     * Register the "make:module" console command.
     *
     * @return void
     */
    protected function registerModuleMakeCommand()
    {
        $this->app->singleton('modules.make', function ($app) {
            return new Console\ModuleMakeCommand($app['files']);
        });
    }
    
    /**
     * Extend the "make:controller" console command.
     *
     * @return void
     */
    protected function registerControllerMakeCommand()
    {
        $this->app->singleton('modules.controller.make', function ($app) {
            return new Console\ControllerMakeCommand($app['files']);
        });
    }
    
    /**
     * Extend the "make:event" console command.
     *
     * @return void
     */
    protected function registerEventMakeCommand()
    {
        $this->app->singleton('modules.event.make', function ($app) {
            return new Console\EventMakeCommand($app['files']);
        });
    }
    
    /**
     * Extend the "make:job" console command.
     *
     * @return void
     */
    protected function registerJobMakeCommand()
    {
        $this->app->singleton('modules.job.make', function ($app) {
            return new Console\JobMakeCommand($app['files']);
        });
    }
    
    /**
     * Extend the "make:listener" console command.
     *
     * @return void
     */
    protected function registerListenerMakeCommand()
    {
        $this->app->singleton('modules.listener.make', function ($app) {
            return new Console\ListenerMakeCommand($app['files']);
        });
    }
    
    /**
     * Extend the "make:mail" console command.
     *
     * @return void
     */
    protected function registerMailMakeCommand()
    {
        $this->app->singleton('modules.mail.make', function ($app) {
            return new Console\MailMakeCommand($app['files']);
        });
    }
    
    /**
     * Extend the "make:model" console command.
     *
     * @return void
     */
    protected function registerModelMakeCommand()
    {
        $this->app->singleton('modules.model.make', function ($app) {
            return new Console\ModelMakeCommand($app['files']);
        });
    }
    
    /**
     * Extend the "make:notification" console command.
     *
     * @return void
     */
    protected function registerNotificationMakeCommand()
    {
        $this->app->singleton('modules.notification.make', function ($app) {
            return new Console\NotificationMakeCommand($app['files']);
        });
    }
    
    /**
     * Extend the "make:observer" console command.
     *
     * @return void
     */
    protected function registerObserverMakeCommand()
    {
        $this->app->singleton('modules.observer.make', function ($app) {
            return new Console\ObserverMakeCommand($app['files']);
        });
    }

    /**
     * Extend the "make:request" console command.
     *
     * @return void
     */
    protected function registerRequestMakeCommand()
    {
        $this->app->singleton('modules.request.make', function ($app) {
            return new Console\RequestMakeCommand($app['files']);
        });
    }

    /**
     * Extend the "make:resource" console command.
     *
     * @return void
     */
    protected function registerResourceMakeCommand()
    {
        $this->app->singleton('modules.resource.make', function ($app) {
            return new Console\ResourceMakeCommand($app['files']);
        });
    }
}
