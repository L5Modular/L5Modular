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
        $this->getComponentGenerationOptions($component, $options);

        $this->call("make:module:{$component}", $options);

        if ($component === 'migration') {
            $this->info("Migration created successfully.");
        }
    }

    /**
     * Individual options for the make commands
     * Reduce the cognitive complexity of generateComponent method
     *
     * @param  string  $component
     *
     * @return array
     */
    protected function getComponentGenerationOptions(string $component, array &$options)
    {
        switch ($component) {
            case 'controller':
                $options['name'] = "{$this->module}Controller";
                $options['--welcome'] = true;
                break;

            case 'view':
                $options['name'] = 'welcome';
                break;

            case 'translation':
                $options['name'] = 'example';
                $options['--language'] = 'en';
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

            case 'config':
            case 'helpers':
                unset($options['name']);
                break;
        }

        return $options;
    }

    protected function generateRoutes()
    {
        $types = config("modules.specific.{$this->module}.routing", config('modules.default.routing'));
        $options = ['--module' => $this->module, '--quiet' => true];

        $skip = true;
        $allowed = [ 'web', 'api', 'simple' ];
        foreach ($types as $type) {
            if (in_array($type, $allowed)) {
                $options["--{$type}"] = true;
                $skip = false;
            }
        }

        if (! $skip) {
            $this->call("make:module:route", $options);
            $this->info("Routes created successfully.");
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'Module name.'],
        ];
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
