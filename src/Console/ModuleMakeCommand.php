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

        $this->generateModule();

        $this->info('Module created successfully.');
    }

    protected function generateModule()
    {
        $components = config('modules.generate', []);
        foreach ($components as $component => $active) {
            if ($active) {
                switch ($component) {
                    case 'controller':
                    case 'model':
                    case 'view':
                    case 'translation':
                    case 'routes':
                    case 'migration':
                    case 'seeder':
                    case 'factory':
                    case 'helpers':
                        $method = "generate" . ucfirst($component);
                        $this->$method();
                        break;
                }
            }
        }
    }

    protected function generateController()
    {
        $path = $this->prepareStubGeneration('controllers', 'controller.stub');
        $name = "{$this->name}Controller";
        $this->saveFile("Controller", $name, [ 'class' =>  "Modules/{$this->name}/{$path}/{$name}" ]);
    }

    protected function generateModel()
    {
        $path = $this->prepareStubGeneration('models', 'model.stub');
        $this->saveFile("Model", $this->name, [ 'class' => "Modules/{$this->name}/{$path}/{$this->name}" ]);
    }

    protected function generateView()
    {
        $path = $this->prepareStubGeneration('views', 'resources/view.stub');
        $this->saveFile("View", 'index.blade', [ 'file' => "Modules/{$this->name}/{$path}/index.blade.php" ]);
    }

    protected function generateTranslation()
    {
        $path = $this->prepareStubGeneration('translations', 'resources/translation.stub');
        $this->saveFile("Translation", 'en', [ 'file' => "Modules/{$this->name}/{$path}/en.php" ]);
    }

    protected function generateRoutes()
    {
        $types = config("modules.specific.{$this->name}.routing", config('modules.default.routing'));
        foreach ($types as $type) {
            $this->generateRoute($type);
        }
    }

    protected function generateRoute(string $type)
    {
        if ($type === 'simple') $file = 'routes.php';
        else $file = "{$type}.php";

        if (in_array($type, [ 'web', 'api', 'simple' ])) {
            $path = $this->prepareStubGeneration('routes', "routes/{$type}.stub");
            $file = "Modules/{$this->name}/{$path}/{$file}";
            $this->saveFile("Routes", $type, compact('file'));
        }
    }

    protected function generateMigration()
    {
        $path = $this->getConfiguredFolder('migrations');
        $path = str_replace('//', '/', "Modules/{$this->name}/{$path}");

        // needs the temp name to generate the folder
        $this->makeDirectory(app_path($path) . '/migration.php');

        $table = Str::plural(Str::snake($this->name));
        $this->call('make:migration', ['name' => "create_{$table}_table", '--create' => $table, '--path' => "app/{$path}"]);
    }

    protected function generateSeeder()
    {
        $path = $this->prepareStubGeneration('seeds', 'database/seeder.stub');
        $this->saveFile("Seeder", $this->name, [ 'class' =>  "Modules/{$this->name}/{$path}/{$this->name}Seeder" ]);
    }

    protected function generateFactory()
    {
        $path = $this->prepareStubGeneration('factories', 'database/factory.stub');

        $modelPath = $this->getConfiguredFolder('models');
        $fullModelClass = $this->qualifyClass(str_replace('//', '/', "Modules/{$this->name}/{$modelPath}/{$this->name}"));
        $content = str_replace(['DummyFullModelClass', 'DummyModelClass'], [ $fullModelClass, $this->name ], $this->stub);

        $this->saveFile("Factory", $this->name, [
            'class' =>  "Modules/{$this->name}/{$path}/{$this->name}Factory",
            'content' => $content,
        ]);
    }

    protected function generateHelpers()
    {
        $path = $this->prepareStubGeneration('helpers', 'helpers.stub');
        $this->saveFile("Helpers", 'helpers', [ 'file' => "Modules/{$this->name}/{$path}/helpers.php" ]);
    }

    /**
     * Prepare stub content to be saved
     *
     * @param  string  $component
     * @param  string  $stub
     * @return string
     */
    protected function prepareStubGeneration(string $component, string $stub)
    {
        $path = $this->getConfiguredFolder($component);

        $stub = $this->files->get(__DIR__ . "/stubs/{$stub}");
        $this->stub = str_replace([ 'DummyTitle', 'DummyUCtitle' ], [ $this->getNameInput(), $this->name ], $stub);

        return $path;
    }

    /**
     * Save stub content to file
     *
     * @param  string  $type
     * @param  string  $name
     * @param  array   $options
     * @return string
     */
    protected function saveFile(string $type, string $name, array $options)
    {
        if (isset($options['class'])) {
            $class = $this->qualifyClass(str_replace('//', '/', $options['class']));
            $content = $this->replaceNamespace($this->stub, $class)->replaceClass($this->stub, $class);

            $file = $this->getPath($class);
        } elseif (isset($options['file'])) {
            $file = app_path(str_replace('//', '/', $options['file']));
            $content = $this->stub;
        }

        if (isset($options['content'])) {
            $content = $options['content'];
        }

        if (isset($file) && isset($content)) {
            $this->makeDirectory($file);
            $this->files->put($file, $content);
            $this->line("<fg=green>Created {$type}:</> {$name}");
        }
    }

    /**
     * Get the configured path for the asked component.
     *
     * @param  string  $component
     * @return string
     */
    protected function getConfiguredFolder(string $component) {
        return config("modules.specific.{$this->name}.structure.{$component}", config("modules.default.structure.{$component}"));
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
     * @codeCoverageIgnore
     * @return string
     */
    protected function getStub()
    {
        return $this->currentStub;
    }
}
