<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Files To Generate
    |--------------------------------------------------------------------------
    |
    | If there are some files in general to generate
    | you can specify them here.
    |
    */

    'generate' => [
        'controller' => true,
        'model' => true,
        'view' => true,
        'translation' => true,
        'routes' => true,
        'migration' => false,
        'seeder' => false,
        'factory' => false,
        'helpers' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Configuration
    |--------------------------------------------------------------------------
    |
    | Everything defined in the "default" config will be used
    | in the artisan make command and for every module
    | that is not defined otherwise,
    |
    */

    'default' => [

        /*
        |--------------------------------------------------------------------------
        | Type Of Routing
        |--------------------------------------------------------------------------
        |
        | If you need / don't need different route files for web and api
        | you can change the array entries like you need them.
        |
        | Supported: "web", "api", "simple"
        |
        */

        'routing' => [ 'web', 'api' ],

        /*
        |--------------------------------------------------------------------------
        | Module Structure
        |--------------------------------------------------------------------------
        |
        | In case your desired module structure differs
        | from the default structure defined here
        | feel free to change it the way you like it,
        |
        */

        'structure' => [
            'controllers' => 'Http/Controllers',
            'events' => 'Events',
            'factories' => 'database/factories',
            'jobs' => 'Jobs',
            'listeners' => 'Listeners',
            'mails' => 'Mail',
            'migrations' => 'database/migrations',
            'models' => 'Models',
            'notifications' => 'Notifications',
            'observers' => 'Observers',
            'requests' => 'Http/Requests',
            'resources' => 'Http/Resources',
            'routes' => 'routes',
            'seeds' => 'database/seeds',
            'translations' => 'resources/lang',
            'views' => 'resources/views',
            'helpers' => '',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Specific Configuration
    |--------------------------------------------------------------------------
    |
    | In the "specific" config you can disable individual modules
    | and override every "default" config from above
    | The array key needs to be the module name.
    |
    */

    'specific' => [

        /*
        |--------------------------------------------------------------------------
        | Example Module
        |--------------------------------------------------------------------------
        |
        | This type of configuration would you allow
        | to use modules from L5Modular v1
        |
        | 'ExampleModule' => [
        |     'enabled' => false,
        |     'routing' => [ 'simple' ],
        |     'structure' => [
        |         'views' => 'Views',
        |         'translations' => 'Translations',
        |     ],
        | ],
        */

    ],
];
