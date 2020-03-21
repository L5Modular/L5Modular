<?php

namespace ArtemSchander\L5Modular\Traits;

trait MakesMessage
{
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

        $baseClass = $this->getBaseClass();
        $baseClassPath = dirname($baseClass->getFileName());

        $this->files->put($file, file_get_contents($baseClassPath . '/stubs/markdown.stub'));
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
        $stub = self::STUB;
        return $this->option('markdown')
                        ? __DIR__ . "/../Console/stubs/markdown-{$stub}"
                        : __DIR__ . "/../Console/stubs/{$stub}";
    }
}
