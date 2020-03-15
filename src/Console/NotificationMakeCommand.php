<?php

namespace ArtemSchander\L5Modular\Console;

use ArtemSchander\L5Modular\Traits\ConfiguresFolder;
use ArtemSchander\L5Modular\Traits\HasModuleOption;
use Illuminate\Foundation\Console\NotificationMakeCommand as BaseNotificationMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class NotificationMakeCommand extends BaseNotificationMakeCommand
{
    use ConfiguresFolder, HasModuleOption;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:module:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new notification class in a module';

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle()
    {
        $this->initModuleOption();

        return $this->module ? parent::handle() : false;
    }

    /**
     * Write the Markdown template for the mailable.
     *
     * @return void
     */
    protected function writeMarkdownTemplate()
    {
        $path = app_path() . '/Modules/' . Str::studly($this->module) . '/' . $this->getConfiguredFolder('views') . '/' . str_replace('.', '/', $this->option('markdown')) . '.blade.php';

        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0755, true);
        }

        $base_class = new \ReflectionClass(BaseNotificationMakeCommand::class);

        $base_class_path = dirname($base_class->getFileName());

        $this->files->put($path, file_get_contents($base_class_path . '/stubs/markdown.stub'));
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Modules\\' . Str::studly($this->module) . '\\' . $this->getConfiguredFolder('notifications');
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        $options = parent::getOptions();

        $options[] = ['module', null, InputOption::VALUE_OPTIONAL, 'Generate a notification in a certain module'];

        return $options;
    }
}
