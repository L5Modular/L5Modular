<?php

namespace ArtemSchander\L5Modular\Traits;

use ArtemSchander\L5Modular\Console;

trait RegisteresCommands
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'ModuleList' => 'command.module.list',
        'ModuleMake' => 'command.module.make',

        'ControllerMake' => 'command.module.controller.make',
        'EventMake' => 'command.module.event.make',
        'FactoryMake' => 'command.module.factory.make',
        'JobMake' => 'command.module.job.make',
        'ViewMake' => 'command.module.view.make',
        'TranslationMake' => 'command.module.translation.make',
        'RouteMake' => 'command.module.route.make',
        'ListenerMake' => 'command.module.listener.make',
        'MailMake' => 'command.module.mail.make',
        'ModelMake' => 'command.module.model.make',
        'MigrateMake' => 'command.module.migrate.make',
        'NotificationMake' => 'command.module.notification.make',
        'ObserverMake' => 'command.module.observer.make',
        'RequestMake' => 'command.module.request.make',
        'ResourceMake' => 'command.module.resource.make',
        'SeederMake' => 'command.module.seeder.make',
        'HelpersMake' => 'command.module.helpers.make',
    ];

    /**
     * Register the given commands.
     *
     * @param  array  $commands
     * @return void
     */
    protected function registerCommands()
    {
        foreach (array_keys($this->commands) as $command) {
            call_user_func_array([$this, "register{$command}Command"], []);
        }

        $this->commands(array_values($this->commands));
    }

    /**
     * Register the "module:list" command.
     *
     * @return void
     */
    protected function registerModuleListCommand()
    {
        $this->app->singleton('command.module.list', function ($app) {
            $files = $app['files'];
            return new Console\ModuleListCommand($files);
        });
    }

    /**
     * Register the "make:module" command.
     *
     * @return void
     */
    protected function registerModuleMakeCommand()
    {
        $this->app->singleton('command.module.make', function ($app) {
            $files = $app['files'];
            return new Console\ModuleMakeCommand($files);
        });
    }

    /**
     * Register the "make:module:controller" command.
     *
     * @return void
     */
    protected function registerControllerMakeCommand()
    {
        $this->app->singleton('command.module.controller.make', function ($app) {
            $files = $app['files'];
            return new Console\ControllerMakeCommand($files);
        });
    }

    /**
     * Register the "make:module:event" command.
     *
     * @return void
     */
    protected function registerEventMakeCommand()
    {
        $this->app->singleton('command.module.event.make', function ($app) {
            $files = $app['files'];
            return new Console\EventMakeCommand($files);
        });
    }

    /**
     * Register the "make:module:factory" command.
     *
     * @return void
     */
    protected function registerFactoryMakeCommand()
    {
        $this->app->singleton('command.module.factory.make', function ($app) {
            $files = $app['files'];
            return new Console\FactoryMakeCommand($files);
        });
    }

    /**
     * Register the "make:module:job" command.
     *
     * @return void
     */
    protected function registerJobMakeCommand()
    {
        $this->app->singleton('command.module.job.make', function ($app) {
            $files = $app['files'];
            return new Console\JobMakeCommand($files);
        });
    }

    /**
     * Register the "make:module:view" command.
     *
     * @return void
     */
    protected function registerViewMakeCommand()
    {
        $this->app->singleton('command.module.view.make', function ($app) {
            $files = $app['files'];
            return new Console\ViewMakeCommand($files);
        });
    }

    /**
     * Register the "make:module:translation" command.
     *
     * @return void
     */
    protected function registerTranslationMakeCommand()
    {
        $this->app->singleton('command.module.translation.make', function ($app) {
            $files = $app['files'];
            return new Console\TranslationMakeCommand($files);
        });
    }

    /**
     * Register the "make:module:route" command.
     *
     * @return void
     */
    protected function registerRouteMakeCommand()
    {
        $this->app->singleton('command.module.route.make', function ($app) {
            $files = $app['files'];
            return new Console\RouteMakeCommand($files);
        });
    }

    /**
     * Register the "make:module:listener" command.
     *
     * @return void
     */
    protected function registerListenerMakeCommand()
    {
        $this->app->singleton('command.module.listener.make', function ($app) {
            $files = $app['files'];
            return new Console\ListenerMakeCommand($files);
        });
    }

    /**
     * Register the "make:module:mail" command.
     *
     * @return void
     */
    protected function registerMailMakeCommand()
    {
        $this->app->singleton('command.module.mail.make', function ($app) {
            $files = $app['files'];
            return new Console\MailMakeCommand($files);
        });
    }

    /**
     * Register the "make:module:model" command.
     *
     * @return void
     */
    protected function registerModelMakeCommand()
    {
        $this->app->singleton('command.module.model.make', function ($app) {
            $files = $app['files'];
            return new Console\ModelMakeCommand($files);
        });
    }

    /**
     * Register the "make:module:migration" command.
     *
     * @return void
     */
    protected function registerMigrateMakeCommand()
    {
        $this->app->singleton('command.module.migrate.make', function ($app) {
            // Once we have the migration creator registered, we will create the command
            // and inject the creator. The creator is responsible for the actual file
            // creation of the migrations, and may be extended by these developers.
            $creator = $app['migration.creator'];

            $files = $app['files'];
            $composer = $app['composer'];
            return new Console\MigrateMakeCommand($files, $creator, $composer);
        });
    }

    /**
     * Register the "make:module:notification" command.
     *
     * @return void
     */
    protected function registerNotificationMakeCommand()
    {
        $this->app->singleton('command.module.notification.make', function ($app) {
            $files = $app['files'];
            return new Console\NotificationMakeCommand($files);
        });
    }

    /**
     * Register the "make:module:observer" command.
     *
     * @return void
     */
    protected function registerObserverMakeCommand()
    {
        $this->app->singleton('command.module.observer.make', function ($app) {
            $files = $app['files'];
            return new Console\ObserverMakeCommand($files);
        });
    }

    /**
     * Register the "make:module:request" command.
     *
     * @return void
     */
    protected function registerRequestMakeCommand()
    {
        $this->app->singleton('command.module.request.make', function ($app) {
            $files = $app['files'];
            return new Console\RequestMakeCommand($files);
        });
    }

    /**
     * Register the "make:module:resource" command.
     *
     * @return void
     */
    protected function registerResourceMakeCommand()
    {
        $this->app->singleton('command.module.resource.make', function ($app) {
            $files = $app['files'];
            return new Console\ResourceMakeCommand($files);
        });
    }

    /**
     * Register the "make:module:seeder" command.
     *
     * @return void
     */
    protected function registerSeederMakeCommand()
    {
        $this->app->singleton('command.module.seeder.make', function ($app) {
            $files = $app['files'];
            $composer = $app['composer'];
            return new Console\SeederMakeCommand($files, $composer);
        });
    }

    /**
     * Register the "make:module:helpers" command.
     *
     * @return void
     */
    protected function registerHelpersMakeCommand()
    {
        $this->app->singleton('command.module.helpers.make', function ($app) {
            $files = $app['files'];
            return new Console\HelpersMakeCommand($files);
        });
    }
}
