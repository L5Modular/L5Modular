<?php

namespace ArtemSchander\L5Modular\Console;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

class ModuleMakeCommand extends GeneratorCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:module';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module (folder structure)';

    /**
     * The studly case module name
     *
     * @var string
     */
    protected $module;

    /**
     * The fully qualified module path
     *
     * @var string
     */
    protected $path;

    /**
     * The current stub.
     *
     * @var string
     */
    protected $stub;

    /**
     * Execute the console command >= L5.5.
     *
     * @return void
     */
    public function handle()
    {
        $this->name = Str::studly($this->getNameInput());
        $this->path = app_path("Modules/{$this->name}");

        // check if module exists
        if ($this->files->exists($this->path)) {
            return $this->error('Module already exists!');
        }

        $components = config('modules.generate', []);
        foreach ($components as $component => $active) {
            if ($active) {
                switch ($component) {
                    case 'controller':
                        $this->generateController();
                        break;

                    case 'model':
                        $this->generateModel();
                        break;

                    case 'view':
                        $this->generateView();
                        break;

                    case 'translation':
                        $this->generateTranslation();
                        break;

                    case 'routes':
                        $this->generateRoutes();
                        break;

                    case 'migration':
                        $this->generateMigration();
                        break;

                    case 'seeder':
                        $this->generateSeeder();
                        break;

                    case 'factory':
                        $this->generateFactory();
                        break;

                    case 'helpers':
                        $this->generateHelpers();
                        break;
                }
            }
        }

        $this->info('Module created successfully.');
    }

    public function fire()
    {
        return $this->handle();
    }

    protected function generateController()
    {
        $name = "{$this->name}Controller";
        $path = config("modules.specific.{$this->name}.structure.controllers", config('modules.default.structure.controllers'));
        $class = $this->qualifyClass(str_replace('//', '/', "Modules/{$this->name}/{$path}/{$name}"));

        $this->stub = $this->files->get(__DIR__ . '/stubs/controller.stub');
        $content = $this->replaceName($this->stub)->replaceNamespace($this->stub, $class)->replaceClass($this->stub, $class);

        $file = $this->getPath($class);
        $this->makeDirectory($file);

        $this->files->put($file, $content);

        $this->line("<fg=green>Created Controller:</> {$name}");
    }

    protected function generateModel()
    {
        $path = config("modules.specific.{$this->name}.structure.models", config('modules.default.structure.models'));
        $class = $this->qualifyClass(str_replace('//', '/', "Modules/{$this->name}/{$path}/{$this->name}"));

        $this->stub = $this->files->get(__DIR__ . '/stubs/model.stub');
        $content = $this->replaceName($this->stub)->replaceNamespace($this->stub, $class)->replaceClass($this->stub, $class);

        $file = $this->getPath($class);
        $this->makeDirectory($file);

        $this->files->put($file, $content);

        $this->line("<fg=green>Created Model:</> {$this->name}");
    }

    protected function generateView()
    {
        $path = config("modules.specific.{$this->name}.structure.views", config('modules.default.structure.views'));

        $this->stub = $this->files->get(__DIR__ . '/stubs/resources/view.stub');
        $this->replaceName($this->stub);

        $file = app_path(str_replace('//', '/', "Modules/{$this->name}/{$path}/index.blade.php"));
        $this->makeDirectory($file);

        $this->files->put($file, $this->stub);

        $this->line('<fg=green>Created View:</> index.blade');
    }

    protected function generateTranslation()
    {
        $path = config("modules.specific.{$this->name}.structure.translations", config('modules.default.structure.translations'));

        $this->stub = $this->files->get(__DIR__ . '/stubs/resources/translation.stub');
        $this->replaceName($this->stub);

        $file = app_path(str_replace('//', '/', "Modules/{$this->name}/{$path}/en.php"));
        $this->makeDirectory($file);

        $this->files->put($file, $this->stub);

        $this->line('<fg=green>Created Translation:</> en');
    }

    protected function generateRoutes()
    {
        $path = config("modules.specific.{$this->name}.structure.routes", config('modules.default.structure.routes'));
        $types = config("modules.specific.{$this->name}.routing", config('modules.default.routing'));

        foreach ($types as $type) {

            $file = null;
            switch ($type) {
                case 'web':
                case 'api':
                    $this->stub = $this->files->get(__DIR__ . "/stubs/routes/{$type}.stub");
                    $file = app_path(str_replace('//', '/', "Modules/{$this->name}/{$path}/{$type}.php"));
                    $this->line("<fg=green>Created Routes:</> {$type}");
                    break;

                    break;

                // TODO
                case 'console':
                    break;

                // TODO
                case 'channels':
                    break;

                case 'simple':
                    $this->stub = $this->files->get(__DIR__ . "/stubs/routes/{$type}.stub");
                    $file = app_path(str_replace('//', '/', "Modules/{$this->name}/{$path}/routes.php"));
                    $this->line('<fg=green>Created Routes:</> routes');
                    break;
            }

            if ($file) {
                $this->replaceName($this->stub);
                $this->makeDirectory($file);
                $this->files->put($file, $this->stub);
            }
        }
    }

    protected function generateMigration()
    {
        $path = config("modules.specific.{$this->name}.structure.migrations", config('modules.default.structure.migrations'));
        $path = str_replace('//', '/', "Modules/{$this->name}/{$path}");

        // needs the temp name to generate the folder
        $this->makeDirectory(app_path($path) . '/migration.php');

        $table = Str::plural(Str::snake($this->name));
        $this->call('make:migration', ['name' => "create_{$table}_table", '--create' => $table, '--path' => "app/{$path}"]);
    }

    protected function generateSeeder()
    {
        $path = config("modules.specific.{$this->name}.structure.seeds", config('modules.default.structure.seeds'));
        $class = $this->qualifyClass(str_replace('//', '/', "Modules/{$this->name}/{$path}/{$this->name}Seeder"));

        $this->stub = $this->files->get(__DIR__ . '/stubs/database/seeder.stub');
        $content = $this->replaceName($this->stub)->replaceNamespace($this->stub, $class)->replaceClass($this->stub, $class);

        $file = $this->getPath($class);
        $this->makeDirectory($file);

        $this->files->put($file, $content);

        $this->line("<fg=green>Created Seeder:</> {$this->name}Seeder");
    }

    protected function generateFactory()
    {
        $path = config("modules.specific.{$this->name}.structure.factories", config('modules.default.structure.factories'));
        $class = $this->qualifyClass(str_replace('//', '/', "Modules/{$this->name}/{$path}/{$this->name}Factory"));

        $this->stub = $this->files->get(__DIR__ . '/stubs/database/factory.stub');
        $this->replaceName($this->stub);

        $modelPath = config("modules.specific.{$this->name}.structure.models", config('modules.default.structure.models'));
        $fullModelClass = $this->qualifyClass(str_replace('//', '/', "Modules/{$this->name}/{$modelPath}/{$this->name}"));
        $content = str_replace(['DummyFullModelClass', 'DummyModelClass'], [ $fullModelClass, $this->name ], $this->stub);

        $file = $this->getPath($class);
        $this->makeDirectory($file);

        $this->files->put($file, $content);

        $this->line("<fg=green>Created Factory:</> {$this->name}Factory");
    }

    protected function generateHelpers()
    {
        $path = config("modules.specific.{$this->name}.structure.helpers", config('modules.default.structure.helpers'));

        $this->stub = $this->files->get(__DIR__ . '/stubs/helpers.stub');
        $this->replaceName($this->stub);

        $file = app_path(str_replace('//', '/', "Modules/{$this->name}/{$path}/helpers.php"));
        $this->makeDirectory($file);

        $this->files->put($file, $this->stub);

        $this->line("<fg=green>Created Helpers:</> helpers");
    }

    /**
     * Get the full namespace name for a given class.
     *
     * @param  string  $name
     * @return string
     */
    protected function getNamespace($name)
    {
        $name = str_replace('\\routes\\', '\\', $name);
        return trim(implode('\\', array_map('ucfirst', array_slice(explode('\\', Str::studly($name)), 0, -1))), '\\');
    }

    /**
     * Replace the name for the given stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function replaceName(&$stub)
    {
        $stub = str_replace('DummyTitle', $this->getNameInput(), $stub);
        $stub = str_replace('DummyUCtitle', $this->name, $stub);
        return $this;
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $class = class_basename($name);
        return str_replace('DummyClass', $class, $stub);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            ['name', InputArgument::REQUIRED, 'Module name.'],
        );
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->currentStub;
    }
}
