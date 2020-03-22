<?php

namespace ArtemSchander\L5Modular\Console;

use ArtemSchander\L5Modular\Traits\ConfiguresFolder;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class ModuleMakeCommand extends GeneratorCommand
{
    use ConfiguresFolder;

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
        $this->module = Str::studly($this->getNameInput());
        $this->path = $this->laravel->path(str_replace('//', '/', "Modules/{$this->module}/"));

        // check if module exists
        if ($this->files->exists($this->path)) {
            return $this->error('Module already exists!');
        }

        $this->generateModule();

        // $this->info('Module created successfully.');
        $this->line('<info>Module</info>' . " \"{$this->module}\" " . '<info>created successfully.</info>');
    }

    protected function generateModule()
    {
        $this->files->makeDirectory($this->path, 0755, true);

        $components = config('modules.generate', []);
        foreach ($components as $component => $active) {
            if ($active) {
                $method = "generate" . ucfirst($component);
                if (method_exists($this, $method)) {
                    $this->$method();
                } else {
                    $this->generateComponent($component);
                }
            }
        }
    }

    /**
     * Generate a component by executing
     * the corresponding artisan make command
     *
     * @param  string  $component
     *
     * @return void
     */
    protected function generateComponent(string $component)
    {
        $name = $this->module;
        $options = ['name' => $name, '--module' => $this->module, '--quiet' => true];

        switch ($component) {
            case 'controller':
                $options['name'] = "{$this->module}Controller";
                $options['--welcome'] = true;
                break;

            case 'translation':
                $options['name'] = 'en';
                break;

            case 'seeder':
                $options['name'] = "{$this->module}Seeder";
                break;

            case 'factory':
                $options['name'] = "{$this->module}Factory";
                $options['--model'] = $this->module;
                break;

            case 'migration':
                $table = Str::plural(Str::snake($this->module));
                $options['name'] = "create_{$table}_table";
                $options['--create'] = $table;
                break;

            case 'helpers':
                unset($options['name']);
                break;
        }

        $this->call("make:module:{$component}", $options);

        if ($component === 'migration') {
            $this->info("Migration created successfully.");
        }
    }

    protected function generateView()
    {
        $path = $this->prepareStubGeneration('views', 'resources/view.stub');
        $this->saveFile('View', [ 'file' => "Modules/{$this->module}/{$path}/welcome.blade.php" ]);
    }

    protected function generateRoutes()
    {
        $types = config("modules.specific.{$this->module}.routing", config('modules.default.routing'));
        foreach ($types as $type) {
            $this->generateRoute($type);
        }
        $this->info("Routes created successfully.");
    }

    protected function generateRoute(string $type)
    {
        if ($type === 'simple') $file = 'routes.php';
        else $file = "{$type}.php";

        $allowed = [ 'web', 'api', 'simple' ];
        if (in_array($type, $allowed)) {
            $path = $this->prepareStubGeneration('routes', "routes/{$type}.stub");
            $file = "Modules/{$this->module}/{$path}/{$file}";

            $quiet = true;
            $this->saveFile('Routes', compact('file', 'quiet'));
        }
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
        $this->stub = str_replace([ 'DummyTitle', 'DummyModuleName' ], [ $this->getNameInput(), $this->module ], $stub);

        return $path;
    }

    /**
     * Save stub content to file
     *
     * @param  string  $type
     * @param  array   $options
     * @return string
     */
    protected function saveFile(string $type, array $options)
    {
        $file = app_path(str_replace('//', '/', $options['file']));
        $content = $this->stub;

        if (isset($file) && isset($content)) {
            $this->makeDirectory($file);
            $this->files->put($file, $content);
            if (! isset($options['quiet']) || $options['quiet'] === false) {
                $this->info("{$type} created successfully.");
            }
        }
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
        return $this->stub;
    }
}
