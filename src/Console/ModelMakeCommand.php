<?php

namespace ArtemSchander\L5Modular\Console;

use ArtemSchander\L5Modular\Traits\MakesComponent;
use Illuminate\Foundation\Console\ModelMakeCommand as BaseModelMakeCommand;
use Illuminate\Support\Str;

class ModelMakeCommand extends BaseModelMakeCommand
{
    use MakesComponent;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:module:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model class in a module';

    /**
     * The key of the component to be generated.
     */
    const KEY = 'models';

    /**
     * The cli info that will be shown on --help.
     */
    const MODULE_OPTION_INFO = 'Generate a model in a certain module';

    /**
     * Create a model factory for the model.
     *
     * @return void
     */
    protected function createFactory()
    {
        $factory = Str::studly(class_basename($this->argument('name')));

        $this->call('make:module:factory', [
            'name' => "{$factory}Factory",
            '--model' => $this->qualifyClass($this->getNameInput()),
            '--module' => $this->option('module') ? $this->option('module') : null,
            '--quiet' => true,
        ]);
    }

    /**
     * Create a migration file for the model.
     *
     * @return void
     */
    protected function createMigration()
    {
        $table = Str::snake(Str::pluralStudly(class_basename($this->argument('name'))));

        if ($this->option('pivot')) {
            $table = Str::singular($table);
        }

        $this->call('make:module:migration', [
            'name' => "create_{$table}_table",
            '--create' => $table,
            '--module' => $this->option('module') ? $this->option('module') : null,
            '--quiet' => true,
        ]);
    }

    /**
     * Create a seeder file for the model.
     *
     * @return void
     */
    protected function createSeeder()
    {
        $seeder = Str::studly(class_basename($this->argument('name')));

        $this->call('make:module:seeder', [
            'name' => "{$seeder}Seeder",
            '--module' => $this->option('module') ? $this->option('module') : null,
            '--quiet' => true,
        ]);
    }

    /**
     * Create a controller for the model.
     *
     * @return void
     */
    protected function createController()
    {
        $controller = Str::studly(class_basename($this->argument('name')));

        $modelName = $this->qualifyClass($this->getNameInput());

        $this->call('make:module:controller', array_filter([
            'name'  => "{$controller}Controller",
            '--model' => $this->option('resource') || $this->option('api') ? $modelName : null,
            '--api' => $this->option('api'),
            '--module' => $this->option('module') ? $this->option('module') : null,
            '--quiet' => true,
        ]));
    }
}
