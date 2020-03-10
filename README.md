<p align="center"><img width="200" src="http://artekk.de/resources/images/l5modular-logo.png" alt="L5Modular logo"></p>
<h2 align="center">L5Modular</h2>
<p align="center">Keep Your Laravel App Organized</p>

<hr>

<p align="center">
    <a href="https://github.com/Artem-Schander/L5Modular/releases"><img src="https://img.shields.io/github/v/release/artem-schander/L5Modular" alt="GitHub release (latest by date)"></a>
    <a href="https://travis-ci.com/Artem-Schander/L5Modular"><img src="https://img.shields.io/travis/Artem-Schander/L5Modular" alt="Travis (.org)"></a>
    <a href="https://codeclimate.com/github/Artem-Schander/L5Modular"><img src="https://img.shields.io/codeclimate/maintainability-percentage/Artem-Schander/L5Modular" alt="Code Climate maintainability"></a>
    <a href="https://codeclimate.com/github/Artem-Schander/L5Modular"><img src="https://img.shields.io/codeclimate/coverage/Artem-Schander/L5Modular" alt="Code Climate coverage"></a>
    <a href="https://packagist.org/packages/artem-schander/l5-modular"><img src="https://img.shields.io/packagist/dt/artem-schander/l5-modular.svg" alt="Downloads"></a>
    <a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/packagist/l/artem-schander/l5-modular" alt="License"></a>
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
This is how the generated module would look like if not otherwise configured:
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
By default the generation of some components are disabled. In full extent this package can generate and handle the following components:
1. Controllers
2. Models
3. Views
4. Translations
5. Routes
6. Migrations
7. Seeds
8. Factories
9. Helpers

<a name="usage"></a>
## Usage

The generated `RESTful Resource Controller` and the corresponding `routes/web.php` make it easy to dive in. In my example you would see the output from the `Modules/FooBar/Views/index.blade.php` by opening `laravel-project:8000/foo-bar` in your browser.


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

#### Load additional classes

In some cases there is a need to load different additional classes into a module. Since Laravel loads the app using the [PSR-4](http://www.php-fig.org/psr/psr-4/) autoloading standard, you can just add folders and files almost without limitations. The only thing you should keep in mind is to name the file exactly like the class name and to add the correct namespace.

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

#### Update to 1.3.0

Since version 1.3.0 you have to follow the `upper camel case` name convention for the module folder. If you had a `Modules/foo` folder you have to rename it to `Modules/Foo`.

Also there are changes in the `app/config/modules.php` file. Now you have to return an array with the key `enable` instead of `list`.


## License

L5Modular is licensed under the terms of the [MIT License](http://opensource.org/licenses/MIT)
(See LICENSE file for details).

---
