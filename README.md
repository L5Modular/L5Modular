<p align="center"><img width="200" src="http://artekk.de/resources/images/l5modular-logo.png" alt="L5Modular logo"></p>
<h3 align="center">L5Modular</h3>
<p align="center">Keep Your Laravel App Organized</p>
<br>
<p align="center">
    <a href="https://github.com/Artem-Schander/L5Modular/releases"><img src="https://img.shields.io/github/v/release/artem-schander/L5Modular" alt="GitHub release (latest by date)"></a>
    <a href="https://travis-ci.com/Artem-Schander/L5Modular"><img src="https://img.shields.io/travis/Artem-Schander/L5Modular" alt="Travis (.org)"></a>
    <a href="https://codeclimate.com/github/Artem-Schander/L5Modular"><img src="https://img.shields.io/codeclimate/maintainability-percentage/Artem-Schander/L5Modular" alt="Code Climate maintainability"></a>
    <a href="https://codeclimate.com/github/Artem-Schander/L5Modular"><img src="https://img.shields.io/codeclimate/coverage/Artem-Schander/L5Modular" alt="Code Climate coverage"></a>
    <a href="https://packagist.org/packages/artem-schander/l5-modular"><img src="https://img.shields.io/packagist/dt/artem-schander/l5-modular.svg" alt="Downloads"></a>
    <a href="https://github.com/Artem-Schander/L5Modular/blob/master/LICENSE"><img src="https://img.shields.io/packagist/l/artem-schander/l5-modular" alt="License"></a>
</p>

<hr>

This package allows you to organize your Laravel project in a modular manner.  
You can simply drop or generate modules with their own controllers, models, views, routes, etc. into the `app/Modules` folder and go on working with them.

Thanks to zyhn for the ["Modular Structure in Laravel 5" tutorial](http://ziyahanalbeniz.blogspot.com.tr/2015/03/modular-structure-in-laravel-5.html). Well explained and helped a lot.

## Documentation

* [Installation](#installation)
* [Getting started](#getting-started)
* [Usage](#usage)
    * [Views](#views)
    * [Translations](#translations)
    * [Routing](#routing)
    * [Migrations](#migrations)
    * [Factories](#factories)
    * [Load additional classes](#load-additional-classes)
* [Configuration](#configuration)
    * [Publish config file](#publish-config-file)
    * [Generation](#generation)
    * [Default](#default)
    * [Specific](#specific)
* [License](#license)

<br>
<br>

---

## Installation

The easiest way to install this package is through your terminal via Composer.  
Run the following command in a bash prompt from your projects root
```bash
composer require artem-schander/l5-modular
```

<br>
<br>

---

## Getting started

The built in Artisan command `php artisan make:module foo-bar` generates a ready to use module in the `app/Modules` folder.  
Unless otherwise configured, this is how the generated module would look like

```
laravel-project/
    app/
    └── Modules/
        └── FooBar/
            ├── Controllers/
            │   └── FooBarController.php
            ├── Models/
            │   └── FooBar.php
            ├── resources/
            │   ├── views/
            │   │   └── index.blade.php
            │   └── lang/
            │       └── en/
            │           └── example.php
            ├── routes
            │   ├── api.php
            │   └── web.php
            └── helper.php

```

<br>
<br>

---

## Usage

The generated `RESTful Resource Controller` and the corresponding resource route in the `routes/web.php` file, make it easy to dive in.  
In the example from above, you should see the output from the `Modules/FooBar/resources/views/index.blade.php` by opening `http://127.0.0.1/foo-bar` in your browser.

<p align="center"><img src="http://artekk.de/resources/images/l5modular-screenshot.png" alt="L5Modular screenshot"></p>

### Views

To tell Laravel that you want to render a view file from a specific module, you need to use the double-colon syntax.  
The `index.blade.php` from the example module `FooBar` could be rendered like this

```php
return view("FooBar::index");
```

### Translations

For the translations applies the same as for the views. You can access them with the double-colon syntax.

```php
echo trans('FooBar::example.welcome');
```

### Routing

Unless otherwise configured, the service provider will look for the files `routes/web.php` and `routes/api.php` and load them with the corresponding middleware and the controllers namespace.  
That means you can register routes without having to enter the full namespace.

```php
Route::resource('foo-bar', 'FooBarController');
```

### Migrations

Unless otherwise configured, the service provider will expect the migrations inside the `database/migrations/` folder.

### Factories

For the factories applies the same as for the migrations.  
Unless otherwise configured, the service provider will expect the factories inside the `database/factories/` folder.

### Load additional classes

Often enough there is a need to load additional classes into a module. Since Laravel loads the app using the [PSR-4](http://www.php-fig.org/psr/psr-4/) autoloading standard, you can just add folders and files almost without limitations. The only thing you should keep in mind is to name the file exactly like the class name and to add the correct namespace.

F.a. If you want to add the `app/Modules/FooBar/Services/FancyService.php` to your module, you can absolutely do so. The file could then look like this

```php
<?php
namespace App\Modules\FooBar\Services;

class FancyService
{
    public static function doFancyStuff() {
        return 'some output';
    }
}
```

<br>
<br>

---

## Configuration

The behaviour of this package is highly customizable.
You can define which components should be generated, what kind of routing is preferred and the module structure. The routing, the structure and a status is also configurable for every module individually.  
To be able to do the mentioned settings you need a `config/modules.php` file which needs to return an array.

### Publish config file

You can get the config file by executing the following command in a bash prompt from your projects root

```bash
php artisan vendor:publish
```

You will most likely be asked to decide what to publish.

```bash
Which provider or tag's files would you like to publish?:
  [0] Publish files from all providers and tags listed below
  [1] Provider: ArtemSchander\L5Modular\ModuleServiceProvider
```

Choose either `0` to publish everything or the number with `Provider: ArtemSchander\L5Modular\ModuleServiceProvider`.  
Now you can customize the following..

### Generation

By default the generation of some components is disabled.  
The `generate` array accepts boolean values to enable / disable the generation of the component.

```php
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
```

### Default

Everything you configure here, will be applied to all modules unless they have their own settings defined under `specific`.  
The default settings consists of `routing` and `structure`.

```php
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
        'controllers' => 'Controllers',
        'models' => 'Models',
        'views' => 'resources/views',
        'translations' => 'resources/lang',
        'routes' => 'routes',
        'migrations' => 'database/migrations',
        'seeds' => 'database/seeds',
        'factories' => 'database/factories',
        'helpers' => '',
    ],
],
```

#### Routing

Here you can define which type of route files will be generated and loaded. The possible options are: `web` `api` `simple`

```php
'routing' => [ 'web', 'api' ],
```

1. **web**  
The make command will generate a `web.php` file with a predifined resource route.  
The service provider will load the file if it exists, apply the "web" middleware and the "controllers" namespace of the corresponding module.
2. **api**  
The make command will generate an empty `api.php` file.  
The service provider will load the file if it exists, apply the "api" middleware and the "controllers" namespace of the corresponding module.
3. **simple**  
The make command will generate a `routes.php` file with a predifined resource route.  
The service provider will load the file if it exists and apply the "controllers" namespace of the corresponding module.

#### Structure

The structure config accepts an associative array, while the values represent the path to the component stated in the key.

```php
'structure' => [
    'controllers' => 'Controllers',
    'models' => 'Models',
    'views' => 'resources/views',
    'translations' => 'resources/lang',
    'routes' => 'routes',
    'migrations' => 'database/migrations',
    'seeds' => 'database/seeds',
    'factories' => 'database/factories',
    'helpers' => '',
],
```

If the value is an empty string, the component will be generated right into the module folder and expected there by the service provider.

### Specific

Every exception to the default config should be defined here. Besides that is this the right place to disable modules.  
It is important to name the keys exactly like the modules the containing config should affect.

```php
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
```

In every module specific config you can configure the `routing` and the `structure` the same way as it is possible for the default config.

#### Disable a module

To disable a module you need to set the `enabled` setting to `false`.  
The config to disable the FooBar module would then look like this

```php
'FooBar' => [
    'enabled' => false,
],
```

#### Change the routing

To change the routing to load only a simple `routes.php` for the FooBar module would would result in this config

```php
'FooBar' => [
    'routing' => [ 'simple' ],
],
```

#### Change the structure

You can completely customize the structure of each module.

```php
'FooBar' => [
    'routing' => [ 'simple' ],
    'structure' => [
        'controllers' => 'Http/Controllers',
        'models' => 'Entities',
        'views' => 'resources/views',
        'translations' => 'resources/lang',
        'routes' => '',
        'migrations' => 'database/migrations',
        'seeds' => 'database/seeds',
        'factories' => 'database/factories',
        'helpers' => '',
    ],
],
```

This config would cause the following structure to be expected by the service provider

```bash
laravel-project/
    app/
    └── Modules/
        └── FooBar/
            ├── Entities
            │   └── FooBar.php
            ├── Http
            │   └── Controllers
            │       └── FooBarController.php
            ├── database
            │   ├── factories
            │   │   └── FooBarFactory.php
            │   ├── migrations
            │   │   └── xxx_create_foo_bars_table.php
            │   └── seeds
            │       └── FooBarSeeder.php
            ├── resources
            │   ├── lang
            │   │   └── en.php
            │   └── views
            │       └── index.blade.php
            ├── helpers.php
            └── routes.php
```

<br>
<br>

---

## License

L5Modular is licensed under the terms of the [MIT License](http://opensource.org/licenses/MIT)
(See LICENSE file for details).
