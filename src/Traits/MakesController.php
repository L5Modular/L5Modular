<?php

namespace ArtemSchander\L5Modular\Traits;

trait MakesController
{
    /**
     * Generate a controller by executing the artisan make command
     *
     * @return void
     */
    protected function generateController()
    {
        $this->call('make:module:controller', [
            'name' => "{$this->module}Controller",
            '--module' => $this->module,
            '--welcome' => true,
        ]);
    }
}
