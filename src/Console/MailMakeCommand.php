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
		$path = $this->getConfiguredFolder('views');
        $file = str_replace('.', '/', $this->option('markdown')) . '.blade.php';

        $file = str_replace('//', '/', app_path("Modules/{$this->module}/{$path}/{$file}"));

        if (! $this->files->isDirectory(dirname($file))) {
            $this->files->makeDirectory(dirname($file), 0755, true);
        }

        $base_class = new \ReflectionClass(BaseMailMakeCommand::class);

        $base_class_path = dirname($base_class->getFileName());

        $this->files->put($file, file_get_contents($base_class_path . '/stubs/markdown.stub'));
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        return str_replace('DummyModuleName', $this->module, parent::buildClass($name));
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->option('markdown')
                        ? __DIR__.'/stubs/markdown-mail.stub'
                        : __DIR__.'/stubs/mail.stub';
    }
}
