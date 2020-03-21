<?php

namespace ArtemSchander\L5Modular\Traits;

use Illuminate\Support\Str;

trait ReplacesRelatedDataInStub
{
    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $relatedComponent = self::RELATED_COMPONENT;
        $key = Str::plural($relatedComponent);

        $component = $this->option($relatedComponent);

        $ignore = array_merge([ $this->laravel->getNamespace() ], self::UNRELATED_COMPONENT_BEGINNINGS);
        if (! Str::startsWith($component, $ignore)) {
            $relativePart = trim(implode('\\', array_map('ucfirst', explode('/', Str::studly($this->getConfiguredFolder($key))))), '\\');
            $component = $this->laravel->getNamespace() . 'Modules\\' . Str::studly($this->option('module')) . '\\' . $relativePart . '\\' . $component;
        }

        $stub = $this->files->get($this->getStub());
        $stub = $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);

        $search = [ 'NamespacedDummyModel', 'DummyFullEvent', 'DummyEvent' ];
        $replace = [ trim($component, '\\'), trim($component, '\\'), class_basename($component) ];

        return str_replace($search, $replace, parent::buildClass($name));
    }
}
