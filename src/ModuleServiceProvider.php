<?php

namespace ArtemSchander\L5Modular;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Factory;

use ArtemSchander\L5Modular\Services\L5Modular;

class ModuleServiceProvider extends ServiceProvider
{
    use Traits\RegisteresCommands;

    protected $files;

    /**
     * Bootstrap the application services.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
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
        $this->registerConfig($name);

        $enabled = config("{$name}.enabled", true);
        if ($enabled) {
            $this->registerRoutes($name);
            $this->registerHelpers($name);
            $this->registerViews($name);
            $this->registerTranslations($name);
            $this->registerMigrations($name);
            $this->registerFactories($name);
        }
    }

    /**
     * Register the config file for a module by its name
     *
     * @param  string $module
     *
     * @return void
     */
    protected function registerConfig(string $module)
    {
        $key = "modules.specific.{$module}";
        $config = $this->app['config']->get($key, []);

        $file = app_path("Modules/{$module}/config.php");
        if ($this->files->exists($file)) {
            $config = array_merge(require $file, $config);
        }

        if ($config) {
            $this->app['config']->set($key, $config);
            if (! $this->app['config']->get($module)) {
                $this->app['config']->set($module, $config);
            }
        }
    }

    /**
     * Register the routes for a module by its name
     *
     * @param  string $module
     *
     * @return void
     */
    protected function registerRoutes(string $module)
    {
        if (! $this->app->routesAreCached()) {
            extract($this->getRoutingConfig($module));

            foreach ($types as $type) {
                $this->registerRoute($module, $path, $namespace, $type);
            }
        }
    }

    /**
     * Registeres a simgle route
     *
     * @param  string $module
     * @param  string $path
     * @param  string $namespace
     * @param  string $type
     *
     * @return void
     */
    protected function registerRoute(string $module, string $path, string $namespace, string $type)
    {
        if ($type === 'simple') $file = 'routes.php';
        else $file = "{$type}.php";

        $file = str_replace('//', '/', app_path("Modules/{$module}/{$path}/{$file}"));

        $allowed = [ 'web', 'api', 'simple' ];
        if (in_array($type, $allowed) && $this->files->exists($file)) {
            if ($type === 'simple') Route::namespace($namespace)->group($file);
            else Route::middleware($type)->namespace($namespace)->group($file);
        }
    }

    /**
     * Collect the needed data to register the routes
     *
     * @param  string $module
     *
     * @return array
     */
    protected function getRoutingConfig(string $module)
    {
        $types = config("{$module}.routing", config('modules.default.routing'));
        $path = config("{$module}.structure.routes", config('modules.default.structure.routes'));

        $cp = config("{$module}.structure.controllers", config('modules.default.structure.controllers'));
        $namespace = $this->app->getNamespace() . trim("Modules\\{$module}\\" . implode('\\', explode('/', $cp)), '\\');

        return compact('types', 'path', 'namespace');
    }

    /**
     * Register the helpers file for a module by its name
     *
     * @param  string $module
     *
     * @return void
     */
    protected function registerHelpers(string $module)
    {
        if ($file = $this->prepareComponent($module, 'helpers', 'helpers.php')) {
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
    protected function registerViews(string $module)
    {
        if ($views = $this->prepareComponent($module, 'views')) {
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
    protected function registerTranslations(string $module)
    {
        if ($translations = $this->prepareComponent($module, 'translations')) {
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
    protected function registerMigrations(string $module)
    {
        if ($migrations = $this->prepareComponent($module, 'migrations')) {
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
    protected function registerFactories(string $module)
    {
        if ($factories = $this->prepareComponent($module, 'factories')) {
            $this->app->make(Factory::class)->load($factories);
        }
    }

    /**
     * Prepare component registration
     *
     * @param  string $module
     * @param  string $component
     * @param  string $file
     *
     * @return string
     */
    protected function prepareComponent(string $module, string $component, string $file = '')
    {
        $path = config("{$module}.structure.{$component}", config("modules.default.structure.{$component}"));
        $resource = rtrim(str_replace('//', '/', app_path("Modules/{$module}/{$path}/{$file}")), '/');

        if (! ($file && $this->files->exists($resource)) && ! (!$file && $this->files->isDirectory($resource))) {
            $resource = false;
        }
        return $resource;
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerPublishConfig();
        $this->registerCommands();
        $this->registerService();
    }

    /**
     * register config
     *
     * @return void
     */
    protected function registerPublishConfig()
    {
        $configPath = __DIR__ . '/config/modules.php';
        $publishPath = $this->app->configPath('modules.php');

        $this->mergeConfigFrom($configPath, 'modules');
        $this->publishes([ $configPath => $publishPath ], 'config');
    }

    /**
     * register service
     *
     * @return void
     */
    protected function registerService()
    {
        $this->app->singleton('l5modular', function($app) {
            return new L5Modular($app['config'], $app['files']);
        });
    }
}
