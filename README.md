# L5Modular
[![Laravel](https://img.shields.io/badge/laravel-5-orange.svg?style=flat-square)](http://laravel.com)
[![Source](https://img.shields.io/badge/source-Artem_Schander-blue.svg?style=flat-square)](https://github.com/Artem-Schander/L5Modular)
[![Contributor](https://img.shields.io/badge/contributor-Farhan Wazir-blue.svg?style=flat-square)](https://github.com/farhanwazir)
[![Release](https://img.shields.io/github/release/Artem-Schander/L5Modular.svg?style=flat-square)](https://github.com/Artem-Schander/L5Modular/releases)
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](http://opensource.org/licenses/MIT)

This package gives you the ability to use Laravel 5 with module system.
You can simply drop or generate modules with their own controllers, models, views, translations and a routes file into the `app/Modules` folder and go on working with them.

Thanks to zyhn for the ["Modular Structure in Laravel 5" tutorial](http://ziyahanalbeniz.blogspot.com.tr/2015/03/modular-structure-in-laravel-5.html). Well explained and helped a lot.

## Documentation

* [Installation](#installation)
* [Getting started](#getting-started)
* [Usage](#usage)


<a name="installation"></a>
## Installation

The best way to install this package is through your terminal via Composer.

Add the following line to the `composer.json` file and fire `composer update`

```
"artem-schander/l5-modular": "dev-master"
```
Once this operation is complete, simply add the service provider to your project's `config/app.php`

#### Service Provider
```
ArtemSchander\L5Modular\ModuleServiceProvider::class,
```

<a name="getting-started"></a>
## Getting started

The built in Artisan command `php artisan make:module name [--no-migration] [--no-translation]` generates a ready to use module in the `app/Modules` folder and a migration if necessary.

This is how the generated module would look like:
```
laravel-project/
    app/
    |-- Modules/
        |-- foobar/
            |-- Controllers/
                |-- FoobarController.php
            |-- Models/
                |-- Foobar.php
            |-- Views/
                |-- index.blade.php
            |-- Translations/
                |-- en/
                    |-- example.php
            |-- routes.php
                
```

<a name="usage"></a>
## Usage

The generated `RESTful Resource Controller` and the corresponding `routes.php` make it easy to dive in. In my example you could see the output from the `Views/index.blade.php` when you open `laravel-project:8000/foobar` in your browser.


#### Disable modules
In case you want to disable one ore more modules, you can add a `modules.php` into your projects `app/config` folder. This file should return an array with the module names that should be loaded.
F.a:
```
return [
    'list' => array(
        "customer",
        "contract",
        "reporting",
    ),
];
```
In this case L5Modular would only load this three modules `customer` `contract` `reporting`. Every other module in the `app/Modules` folder would not be loaded.

L5Modular will load all modules if there is no modules.php file in the config folder.


## License

L5Modular is licensed under the terms of the [MIT License](http://opensource.org/licenses/MIT)
(See LICENSE file for details).

---
