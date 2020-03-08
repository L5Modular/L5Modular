<?php

namespace ArtemSchander\L5Modular;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Factory;

class ModuleServiceProvider extends ServiceProvider
{
    protected $files;

    /**
     * Bootstrap the application services.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     *
     * @return void
     */
    public function boot(Filesystem $files)
    {
        $this->files = $files;
        if (is_dir(app_path('Modules'))) {

            $modules = array_map('class_basename', $this->files->directories(app_path('Modules')));
            foreach ($modules as $module) {
                // Allow routes to be cached
                $this->registerModule($module);
            }
        }
    }

    /**
     * Register a module by its name
     *
     * @param  string $name
     *
     * @return void
     */
    protected function registerModule(string $name)
    {
        $enabled = config("modules.specific.{$name}.enabled", true);
        if ($enabled) {
            $this->registerModuleRoutes($name);
            $this->registerModuleHelpers($name);
            $this->registerModuleViews($name);
            $this->registerModuleTranslations($name);
            $this->registerModuleMigrations($name);
            $this->registerModuleFactories($name);
        }
    }

    /**
     * Register the routes for a module by its name
     *
     * @param  string $module
     *
     * @return void
     */
    protected function registerModuleRoutes(string $module)
    {
        if (! $this->app->routesAreCached()) {
            $types = config("modules.specific.{$module}.routing", config('modules.default.routing'));
            $path = config("modules.specific.{$module}.structure.routes", config('modules.default.structure.routes'));

            $controllersPath = config("modules.specific.{$module}.structure.controllers", config('modules.default.structure.controllers'));
            $namespace = trim("App\\Modules\\{$module}\\" . implode('\\', explode('/', $controllersPath)), '\\');

            foreach ($types as $type) {
                switch ($type) {
                    case 'web':
                    case 'api':
                        $file = str_replace('//', '/', app_path("Modules/{$module}/{$path}/{$type}.php"));
                        if ($this->files->exists($file)) {
                            Route::middleware($type)
                                ->namespace($namespace)
                                ->group($file);
                        }
                        break;

                        break;

                    // TODO
                    // @codeCoverageIgnoreStart
                    case 'console':
                        break;

                    // TODO
                    case 'channels':
                        break;
                    // @codeCoverageIgnoreEnd

                    case 'simple':
                        $file = str_replace('//', '/', app_path("Modules/{$module}/{$path}/routes.php"));
                        if ($this->files->exists($file)) {
                            Route::namespace($namespace)
                                ->group($file);
                        }
                        break;
                }
            }
        }
    }

    /**
     * Register the helpers file for a module by its name
     *
     * @param  string $module
     *
     * @return void
     */
    protected function registerModuleHelpers(string $module)
    {
        $path = config("modules.specific.{$module}.structure.helpers", config('modules.default.structure.helpers'));
        $file = str_replace('//', '/', app_path("Modules/{$module}/{$path}/helpers.php"));

        if ($this->files->exists($file)) {
            include_once $file;
        }
    }

    /**
     * Register the views for a module by its name
     *
     * @param  string $module
     *
     * @return void
     */
    protected function registerModuleViews(string $module)
    {
        $path = config("modules.specific.{$module}.structure.views", config('modules.default.structure.views'));
        $views = str_replace('//', '/', app_path("Modules/{$module}/{$path}"));

        if ($this->files->isDirectory($views)) {
            $this->loadViewsFrom($views, $module);
        }
    }

    /**
     * Register the translations for a module by its name
     *
     * @param  string $module
     *
     * @return void
     */
    protected function registerModuleTranslations(string $module)
    {
        $path = config("modules.specific.{$module}.structure.translations", config('modules.default.structure.translations'));
        $translations = str_replace('//', '/', app_path("Modules/{$module}/{$path}"));

        if ($this->files->isDirectory($translations)) {
            $this->loadTranslationsFrom($translations, $module);
        }
    }

    /**
     * Register the migrations for a module by its name
     *
     * @param  string $module
     *
     * @return void
     */
    protected function registerModuleMigrations(string $module)
    {
        $path = config("modules.specific.{$module}.structure.migrations", config('modules.default.structure.migrations'));
        $migrations = str_replace('//', '/', app_path("Modules/{$module}/{$path}"));

        if ($this->files->isDirectory($migrations)) {
            $this->loadMigrationsFrom($migrations);
        }
    }

    /**
     * Register the factories for a module by its name
     *
     * @param  string $module
     *
     * @return void
     */
    protected function registerModuleFactories(string $module)
    {
        $path = config("modules.specific.{$module}.structure.factories", config('modules.default.structure.factories'));
        $factories = str_replace('//', '/', app_path("Modules/{$module}/{$path}"));

        if ($this->files->isDirectory($factories)) {
            $this->app->make(Factory::class)->load($factories);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerMakeCommand();
        $this->registerPublishConfig();
    }

    /**
     * undocumented function
     *
     * @return void
     */
    protected function registerPublishConfig()
    {
        $configPath = __DIR__ . '/config/modules.php';
        $publishPath = $this->app->configPath('modules.php');

        $this->publishes([
            $configPath => $publishPath,
        ]);
        $this->mergeConfigFrom($configPath, 'modules');
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
    }
}
