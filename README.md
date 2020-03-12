<p align="center"><img width="200" src="http://artekk.de/resources/images/l5modular-logo.png" alt="L5Modular logo"></p>
<h3 align="center">L5Modular</h3>
<p align="center">Keep Your Laravel App Organized</p>

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
* [Configuration](#configuration)


<a name="installation"></a>
## Installation

The easiest way to install this package is through your terminal via Composer.

Run the following command from your projects root
```shell
composer require artem-schander/l5-modular
```

<a name="getting-started"></a>
## Getting started

The built in Artisan command `php artisan make:module foo-bar` generates a ready to use module in the `app/Modules` folder.  
This is how the generated module would look like, unless otherwise configured:
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

<a name="usage"></a>
## Usage

The generated `RESTful Resource Controller` and the corresponding `routes/web.php` make it easy to dive in. In my example you would see the output from the `Modules/FooBar/Views/index.blade.php` by opening `http://127.0.0.1/foo-bar` in your browser.

#### Views
To tell Laravel that you want to render a view file from a specific module, you need to use the double-colon syntax.  
The `index.blade.php` from the example module `FooBar` could be rendered like this:
```php
return view("FooBar::index");
```

#### Translations
The double-colon syntax applies here too.
```php
echo trans('FooBar::example.welcome');
```

#### Routing
Unless otherwise configured, the service provider will look for the files `routes/web.php` and `routes/api.php` and load them with the corresponding middleware and the controllers namespace.  
That means you can register routes without having to enter the full namespace.
```php
Route::resource('foo-bar', 'FooBarController');
```

#### Migrations
Unless otherwise configured, the service provider will expect the migrations inside the `database/migrations/` folder.

#### Factories
For the factories applies the same as for the migrations.  
Unless otherwise configured, the service provider will expect the factories inside the `database/factories/` folder.

---

#### Load additional classes

Often enough there is a need to load additional classes into a module. Since Laravel loads the app using the [PSR-4](http://www.php-fig.org/psr/psr-4/) autoloading standard, you can just add folders and files almost without limitations. The only thing you should keep in mind is to name the file exactly like the class name and to add the correct namespace.

F.a. If you want to add the `app/Modules/FooBar/Services/FancyService.php` to your module, you can absolutely do so. The file could then look like this:
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

<a name="configuration"></a>
## Configuration

The behaviour of this package is highly customizable.
You can define which components should be generated, what kind of routing is preferred and the module structure. The routing, the structure and a status is also configurable for every module individually.  
To be able to do the mentioned settings you need a `config/modules.php` file which needs to return an array.

You can get the file by executing the following command in your terminal from your projects root:
```bash
php artisan vendor:publish
```
Most likely it will ask you to decide what you want to publish.
```bash
Which provider or tag's files would you like to publish?:
  [0] Publish files from all providers and tags listed below
  [1] Provider: ArtemSchander\L5Modular\ModuleServiceProvider
```
Choose either `0` to publish everything or the number with `Provider: ArtemSchander\L5Modular\ModuleServiceProvider`.  
Now you can customize the following..

#### Generation

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





#### Disable modules
In case you want to disable one ore more modules, you can add a `modules.php` into your projects `app/config` folder. This file should return an array with the module names that should be **loaded**.
F.a:
```php
return [
    'enable' => [
        "customer",
        "contract",
        "reporting",
    ],
];
```
In this case L5Modular would only load this three modules `customer` `contract` `reporting`. Every other module in the `app/Modules` folder would not be loaded.

L5Modular will load all modules if there is no modules.php file in the config folder.

#### Use a single `routes.php` file *(à la Laravel < v5.3)*

Since version 1.4.0 the module structure has slightly changed. Instead of using a single routes file there is a routes folder with the route `web.php` and `api.php`. No panic, the old fashioned routes file will be loaded anyways. So if you like it that way you can stick with the single routes file in the module-root folder.




## License

L5Modular is licensed under the terms of the [MIT License](http://opensource.org/licenses/MIT)
(See LICENSE file for details).

---
