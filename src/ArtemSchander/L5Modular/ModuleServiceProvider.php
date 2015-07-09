<?php namespace ArtemSchander\L5Modular;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider {

	protected $files;

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot() {

		if(is_dir(app_path().'/Modules/')) {

			$modules = (config("modules.list")) ?: array_map('class_basename', $this->files->directories(app_path().'/Modules/'));
			foreach($modules as $module)  {
				
				$routes = app_path().'/Modules/'.$module.'/routes.php';
				$views  = app_path().'/Modules/'.$module.'/Views';
				$trans  = app_path().'/Modules/'.$module.'/Translations';

				if($this->files->exists($routes)) include $routes;
				if($this->files->isDirectory($views)) $this->loadViewsFrom($views, $module);
				if($this->files->isDirectory($trans)) $this->loadTranslationsFrom($trans, $module);
			}
		}

	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register() {

		$this->files = new Filesystem;
		$this->registerMakeCommand();
	}

	/**
	 * Register the "make:module" console command.
	 *
	 * @return Console\ModuleMakeCommand
	 */
	protected function registerMakeCommand() {

		$this->commands('modules.make');
		
		$this->app->bindShared('modules.make', function($app) {
			return new Console\ModuleMakeCommand($this->files);
		});
	}

}
