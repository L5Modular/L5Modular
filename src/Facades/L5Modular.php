<?php

namespace ArtemSchander\L5Modular\Facades;

use Illuminate\Support\Facades\Facade;

class L5Modular extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return 'l5modular';
    }
}
