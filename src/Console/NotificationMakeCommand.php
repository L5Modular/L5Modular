<?php

namespace ArtemSchander\L5Modular\Console;

use Illuminate\Foundation\Console\NotificationMakeCommand as BaseNotificationMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class NotificationMakeCommand extends BaseNotificationMakeCommand
{
    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle()
    {
        $name = $this->qualifyClass($this->getNameInput());
        $path = $this->getPath($name);

        if ($this->hasOption('module') && !$this->files->isDirectory(dirname($path, 2))) {
            $this->error('Module doesn\'t exist.');
            
            return false;
        }

        parent::handle();
    }

    /**
     * Write the Markdown template for the mailable.
     *
     * @return void
     */
    protected function writeMarkdownTemplate()
    {
        if ($this->hasOption('module')) {
            $path = app_path().'/Modules/'.Str::studly($this->option('module')).'/views/'.str_replace('.', '/', $this->option('markdown')).'.blade.php';
        } else {
            $path = resource_path('views/'.str_replace('.', '/', $this->option('markdown'))).'.blade.php';
        }

        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0755, true);
        }

        $base_class = new ReflectionClass(BaseMailMakeCommand::class);
        
        $base_class_path = dirname($base_class->getFileName());

        $this->files->put($path, file_get_contents($base_class_path.'/stubs/markdown.stub'));
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        if ($this->hasOption('module')) {
            return $rootNamespace.'\Modules\\'.Str::studly($this->option('module')).'\Notifications';
        } else {
            return parent::getDefaultNamespace($rootNamespace);   
        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        $options = parent::getOptions();

        $options[] = ['module', null, InputOption::VALUE_OPTIONAL, 'Generate a contorller in a certain module.'];

        return $options;
    }
}
