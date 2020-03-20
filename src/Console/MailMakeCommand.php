<?php

namespace ArtemSchander\L5Modular\Console;

use ArtemSchander\L5Modular\Traits\MakesComponent;
use Illuminate\Foundation\Console\MailMakeCommand as BaseMailMakeCommand;
use Illuminate\Support\Str;

class MailMakeCommand extends BaseMailMakeCommand
{
    use MakesComponent;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:module:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new mail class in a module';

    /**
     * The key of the component to be generated.
     *
     * @var string
     */
    const KEY = 'mails';

    /**
     * The cli info that will be shown on --help.
     */
    const MODULE_OPTION_INFO = 'Generate a mailable in a certain module';

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

        $base_class = new \ReflectionClass(BaseMailMakeCommand::class);

        $base_class_path = dirname($base_class->getFileName());

        $this->files->put($path, file_get_contents($base_class_path . '/stubs/markdown.stub'));
    }
}
