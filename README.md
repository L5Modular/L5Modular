# L5Modular
[![Laravel](https://img.shields.io/badge/laravel-5-orange.svg)](http://laravel.com)
[![Release](https://poser.pugx.org/artem-schander/l5-modular/v/stable)](https://github.com/Artem-Schander/L5Modular/releases)
[![Source](https://img.shields.io/badge/source-Artem_Schander-blue.svg)](https://github.com/Artem-Schander/L5Modular)
[![Contributor](https://img.shields.io/badge/contributor-Farhan Wazir-blue.svg)](https://github.com/farhanwazir)
[![License](https://poser.pugx.org/artem-schander/l5-modular/license)](https://packagist.org/packages/artem-schander/l5-modular)

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

Run the following command from your projects root
```
composer require artem-schander/l5-modular
```
Once this operation is complete, simply add the service provider to your project's `config/app.php` and you're done.

#### Service Provider
```
ArtemSchander\L5Modular\ModuleServiceProvider::class,
```

<a name="getting-started"></a>
## Getting started

The built in Artisan command `php artisan make:module name [--no-migration] [--no-translation]` generates a ready to use module in the `app/Modules` folder and a migration if necessary.

Since version 1.3.0 you can generate modules named with more than one word, like `foo-bar`.

This is how the generated module would look like:
```
laravel-project/
    app/
    └── Modules/
        └── FooBar/
            ├── Controllers/
            │   └── FooBarController.php
            ├── Models/
            │   └── FooBar.php
            ├── Views/
            │   └── index.blade.php
            ├── Translations/
            │   └── en/
            │       └── example.php
            ├── routes
            │   ├── api.php
            │   └── web.php
            └── helper.php
                
```

<a name="usage"></a>
## Usage

The generated `RESTful Resource Controller` and the corresponding `routes.php` make it easy to dive in. In my example you would see the output from the `Modules/FooBar/Views/index.blade.php` when you open `laravel-project:8000/foo-bar` in your browser.


#### Disable modules
In case you want to disable one ore more modules, you can add a `modules.php` into your projects `app/config` folder. This file should return an array with the module names that should be **loaded**.
F.a:
```
return [
    'enable' => array(
        "customer",
        "contract",
        "reporting",
    ),
];
```
In this case L5Modular would only load this three modules `customer` `contract` `reporting`. Every other module in the `app/Modules` folder would not be loaded.

L5Modular will load all modules if there is no modules.php file in the config folder.

#### Use a single `routes.php` file *(à la Laravel < v5.3)*

Since version 1.4.0 the module structure has slightly changed. Instead of using a single routes file there is a routes folder with the route files `web.php` and `api.php`. No panic, the old fashioned routes file will be loaded anyways. So if you like it that way you can stick with the single routes file in the module-root folder.

#### Load additional classes

In some cases there is a need to load different additional classes into a module. Since Laravel loads the app using the PSR-4 autoloading standard, you can just add folders and files almost without limitations. The only thing you should keep in mind is to add the correct namespace.

F.a. If you want to add the `App/Modules/FooBar/Services/FancyService.php` to your module, you can absolutely do so. The file could then look like this:
```
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
