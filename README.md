<p align="center"><img width="200" src="http://artekk.de/resources/images/l5modular-logo.png" alt="L5Modular logo"></p>
<h3 align="center">L5Modular</h3>
<p align="center">Keep Your Laravel App Organized</p>
<br>
<p align="center">
    <a href="https://github.com/Artem-Schander/L5Modular/releases"><img src="https://img.shields.io/github/v/release/artem-schander/L5Modular" alt="GitHub release (latest by date)"></a>
    <a href="https://travis-ci.com/Artem-Schander/L5Modular"><img src="https://img.shields.io/travis/com/Artem-Schander/L5Modular/master" alt="Travis"></a>
    <a href="https://codeclimate.com/github/Artem-Schander/L5Modular"><img src="https://img.shields.io/codeclimate/maintainability-percentage/Artem-Schander/L5Modular" alt="Code Climate maintainability"></a>
    <a href="https://codeclimate.com/github/Artem-Schander/L5Modular"><img src="https://img.shields.io/codeclimate/coverage/Artem-Schander/L5Modular" alt="Code Climate coverage"></a>
    <a href="https://packagist.org/packages/artem-schander/l5-modular"><img src="https://img.shields.io/packagist/dt/artem-schander/l5-modular.svg" alt="Downloads"></a>
<!-- ALL-CONTRIBUTORS-BADGE:START - Do not remove or modify this section -->
<a href='#contributors'><img src='https://img.shields.io/badge/contributors-6-orange.svg' alt='All Contributors'></a>
<!-- ALL-CONTRIBUTORS-BADGE:END -->
    <a href="https://github.com/Artem-Schander/L5Modular/blob/master/LICENSE"><img src="https://img.shields.io/packagist/l/artem-schander/l5-modular" alt="License"></a>
</p>

<hr>

This package allows you to organize your Laravel project in a modular manner.  
You can simply drop or generate modules with their own controllers, models, views, routes, etc. into the `app/Modules` folder and go on working with them.

## Documentation

* [Installation](#installation)
* [Getting started](#getting-started)
* [Usage](#usage)
    * [Artisan Commands](#artisan-commands)
        * [make module](#php-artisan-makemodule)
        * [make controller](#php-artisan-makemodulecontroller)
        * [make resource](#php-artisan-makemoduleresource)
        * [make request](#php-artisan-makemodulerequest)
        * [make model](#php-artisan-makemodulemodel)
        * [make mail](#php-artisan-makemodulemail)
        * [make notification](#php-artisan-makemodulenotification)
        * [make event](#php-artisan-makemoduleevent)
        * [make listener](#php-artisan-makemodulelistener)
        * [make observer](#php-artisan-makemoduleobserver)
        * [make job](#php-artisan-makemodulejob)
        * [make view](#php-artisan-makemoduleview)
        * [make translation](#php-artisan-makemoduletranslation)
        * [make routes](#php-artisan-makemoduleroute)
        * [make migration](#php-artisan-makemodulemigration)
        * [make seeder](#php-artisan-makemoduleseeder)
        * [make factory](#php-artisan-makemodulefactory)
        * [make helpers](#php-artisan-makemodulehelpers)
        * [module list](#php-artisan-modulelist)
    * [Views](#views)
    * [Translations](#translations)
    * [Routing](#routing)
    * [Migrations](#migrations)
    * [Factories](#factories)
    * [Loading additional classes](#loading-additional-classes)
* [Configuration](#configuration)
    * [Publish config file](#publish-config-file)
    * [Generate](#generate)
    * [Default](#default)
    * [Specific](#specific)
* [Contributors](#contributors)
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

#### Requirements

L5Modular v2 requires at least PHP 7.2 and Laravel 5.7  
Older PHP / Laravel versions are supported by L5Modular v1. 

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
        └── FooBar
            ├── Http
            │   └── Controllers
            │       └── FooBarController.php
            ├── Models
            │   └── FooBar.php
            ├── resources
            │   ├── lang
            │   │   └── en.php
            │   └── views
            │       └── welcome.blade.php
            └── routes
                ├── api.php
                └── web.php
```

<br>
<br>

---

## Usage

The welcome method in the generated controller `Http/Controllers/FooBarController.php`, the corresponding route in the `routes/web.php` file and the view `welcome.blade.php`, make it easy to dive in.  
In the example above, you should see the output from the `Modules/FooBar/resources/views/welcome.blade.php` by opening `http://laravel-project.dev/foo-bar` in your browser.

<p align="center"><img src="http://artekk.de/resources/images/l5modular-screenshot.png" alt="L5Modular screenshot"></p>

### Artisan Commands

Besides the mentioned command `php artisan make:module` there are a lot more.  
There is `php artisan module:list` and many of Laravels `artisan:make` commands are extended to generate components right into a module.

<br>

##### `php artisan make:module`
This command generates a full module.  
You can configure which structure and which components should be generated.
<details>
    <summary>Description / Usage / Arguments / Options</summary>

```
Description:
  Create a new module (folder structure)

Usage:
  make:module <name>

Arguments:
  name                  Module name.

Options:
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
      --env[=ENV]       The environment the command should run under
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```
</details>

<br>

##### `php artisan make:module:controller`
This command generates a controller into a module.  
By passing in options you can define what kind of controller and in which module it should be generated.
<details>
    <summary>Description / Usage / Arguments / Options</summary>

```
Description:
  Create a new controller class in a module

Usage:
  make:module:controller [options] [--] <name>

Arguments:
  name                   The name of the class

Options:
      --api              Exclude the create and edit methods from the controller.
      --force            Create the class even if the controller already exists
  -i, --invokable        Generate a single method, invokable controller class.
  -m, --model[=MODEL]    Generate a resource controller for the given model.
  -p, --parent[=PARENT]  Generate a nested resource controller class.
  -r, --resource         Generate a resource controller class.
  -w, --welcome          Generate a controller with a welcome method.
      --module[=MODULE]  Generate a controller in a certain module
  -h, --help             Display this help message
  -q, --quiet            Do not output any message
  -V, --version          Display this application version
      --ansi             Force ANSI output
      --no-ansi          Disable ANSI output
  -n, --no-interaction   Do not ask any interactive question
      --env[=ENV]        The environment the command should run under
  -v|vv|vvv, --verbose   Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```
Example:  
```bash
php artisan make:module:controller MemberController --module=FooBar --model=Member
```
This would create a RESTful Resource Controller `app/Modules/FooBar/Http/Controllers/MemberController.php` and ask you if you want to generate the `Member` model as well, if it doesn't already exist.
</details>

<br>

##### `php artisan make:module:resource`
This command generates a resource into a module.  
By passing in options you can define what kind of resource and in which module it should be generated.
<details>
    <summary>Description / Usage / Arguments / Options</summary>

```
Description:
  Create a new resource class in a module

Usage:
  make:module:resource [options] [--] <name>

Arguments:
  name                   The name of the class

Options:
  -c, --collection       Create a resource collection
      --module[=MODULE]  Generate a resource in a certain module
  -h, --help             Display this help message
  -q, --quiet            Do not output any message
  -V, --version          Display this application version
      --ansi             Force ANSI output
      --no-ansi          Disable ANSI output
  -n, --no-interaction   Do not ask any interactive question
      --env[=ENV]        The environment the command should run under
  -v|vv|vvv, --verbose   Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```
</details>

<br>

##### `php artisan make:module:request`
This command generates a request into a module.  
By passing in the corresponding option you can define in which module the request class should be generated.
<details>
    <summary>Description / Usage / Arguments / Options</summary>

```
Description:
  Create a new request class in a module

Usage:
  make:module:request [options] [--] <name>

Arguments:
  name                   The name of the class

Options:
      --module[=MODULE]  Generate a request in a certain module
  -h, --help             Display this help message
  -q, --quiet            Do not output any message
  -V, --version          Display this application version
      --ansi             Force ANSI output
      --no-ansi          Disable ANSI output
  -n, --no-interaction   Do not ask any interactive question
      --env[=ENV]        The environment the command should run under
  -v|vv|vvv, --verbose   Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```
</details>

<br>

##### `php artisan make:module:model`
This command generates a model into a module.  
By passing in options you can define in which module it should be generated and whether other components should also be generated or not.
<details>
    <summary>Description / Usage / Arguments / Options</summary>

```
Description:
  Create a new model class in a module

Usage:
  make:module:model [options] [--] <name>

Arguments:
  name                   The name of the class

Options:
  -a, --all              Generate a migration, seeder, factory, and resource controller for the model
  -c, --controller       Create a new controller for the model
  -f, --factory          Create a new factory for the model
      --force            Create the class even if the model already exists
  -m, --migration        Create a new migration file for the model
  -s, --seed             Create a new seeder file for the model
  -p, --pivot            Indicates if the generated model should be a custom intermediate table model
  -r, --resource         Indicates if the generated controller should be a resource controller
      --api              Indicates if the generated controller should be an API controller
      --module[=MODULE]  Generate a model in a certain module
  -h, --help             Display this help message
  -q, --quiet            Do not output any message
  -V, --version          Display this application version
      --ansi             Force ANSI output
      --no-ansi          Disable ANSI output
  -n, --no-interaction   Do not ask any interactive question
      --env[=ENV]        The environment the command should run under
  -v|vv|vvv, --verbose   Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```
</details>

<br>

##### `php artisan make:module:mail`
This command generates a mail into a module.  
By passing in options you can define in which module it should be generated and whether a markdown view should also be generated or not.
<details>
    <summary>Description / Usage / Arguments / Options</summary>

```
Description:
  Create a new mail class in a module

Usage:
  make:module:mail [options] [--] <name>

Arguments:
  name                       The name of the class

Options:
  -f, --force                Create the class even if the mailable already exists
  -m, --markdown[=MARKDOWN]  Create a new Markdown template for the mailable
      --module[=MODULE]      Generate a mailable in a certain module
  -h, --help                 Display this help message
  -q, --quiet                Do not output any message
  -V, --version              Display this application version
      --ansi                 Force ANSI output
      --no-ansi              Disable ANSI output
  -n, --no-interaction       Do not ask any interactive question
      --env[=ENV]            The environment the command should run under
  -v|vv|vvv, --verbose       Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```
</details>

<br>

##### `php artisan make:module:notification`
This command generates a notification into a module.  
By passing in options you can define in which module it should be generated and whether a markdown view should also be generated or not.
<details>
    <summary>Description / Usage / Arguments / Options</summary>

```
Description:
  Create a new notification class in a module

Usage:
  make:module:notification [options] [--] <name>

Arguments:
  name                       The name of the class

Options:
  -f, --force                Create the class even if the notification already exists
  -m, --markdown[=MARKDOWN]  Create a new Markdown template for the notification
      --module[=MODULE]      Generate a notification in a certain module
  -h, --help                 Display this help message
  -q, --quiet                Do not output any message
  -V, --version              Display this application version
      --ansi                 Force ANSI output
      --no-ansi              Disable ANSI output
  -n, --no-interaction       Do not ask any interactive question
      --env[=ENV]            The environment the command should run under
  -v|vv|vvv, --verbose       Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```
</details>

<br>

##### `php artisan make:module:event`
This command generates a event into a module.  
By passing in the corresponding option you can define in which module the event class should be generated.
<details>
    <summary>Description / Usage / Arguments / Options</summary>

```
Description:
  Create a new event class in a module

Usage:
  make:module:event [options] [--] <name>

Arguments:
  name                   The name of the class

Options:
      --module[=MODULE]  Generate an event in a certain module
  -h, --help             Display this help message
  -q, --quiet            Do not output any message
  -V, --version          Display this application version
      --ansi             Force ANSI output
      --no-ansi          Disable ANSI output
  -n, --no-interaction   Do not ask any interactive question
      --env[=ENV]        The environment the command should run under
  -v|vv|vvv, --verbose   Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```
</details>

<br>

##### `php artisan make:module:listener`
This command generates a listener into a module.  
By passing in options you can define in which module it should be generated, also the event the listener should listen for and whether the listener should be queued or not.
<details>
    <summary>Description / Usage / Arguments / Options</summary>

```
Description:
  Create a new listener class in a module

Usage:
  make:module:listener [options] [--] <name>

Arguments:
  name                   The name of the class

Options:
  -e, --event[=EVENT]    The event class being listened for
      --queued           Indicates the event listener should be queued
      --module[=MODULE]  Generate a listener in a certain module
  -h, --help             Display this help message
  -q, --quiet            Do not output any message
  -V, --version          Display this application version
      --ansi             Force ANSI output
      --no-ansi          Disable ANSI output
  -n, --no-interaction   Do not ask any interactive question
      --env[=ENV]        The environment the command should run under
  -v|vv|vvv, --verbose   Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```
</details>

<br>

##### `php artisan make:module:observer`
This command generates a observer into a module.  
By passing in options you can define in which module it should be generated and the model the observer should apply to.
<details>
    <summary>Description / Usage / Arguments / Options</summary>

```
Description:
  Create a new observer class in a module

Usage:
  make:module:observer [options] [--] <name>

Arguments:
  name                   The name of the class

Options:
  -m, --model[=MODEL]    The model that the observer applies to.
      --module[=MODULE]  Generate an observer in a certain module
  -h, --help             Display this help message
  -q, --quiet            Do not output any message
  -V, --version          Display this application version
      --ansi             Force ANSI output
      --no-ansi          Disable ANSI output
  -n, --no-interaction   Do not ask any interactive question
      --env[=ENV]        The environment the command should run under
  -v|vv|vvv, --verbose   Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```
</details>

<br>

##### `php artisan make:module:job`
This command generates a job into a module.  
By passing in options you can define in which module it should be generated and whether the job should be synchronous or not.
<details>
    <summary>Description / Usage / Arguments / Options</summary>

```
Description:
  Create a new job class in a module

Usage:
  make:module:job [options] [--] <name>

Arguments:
  name                   The name of the class

Options:
      --sync             Indicates that job should be synchronous
      --module[=MODULE]  Generate a job in a certain module
  -h, --help             Display this help message
  -q, --quiet            Do not output any message
  -V, --version          Display this application version
      --ansi             Force ANSI output
      --no-ansi          Disable ANSI output
  -n, --no-interaction   Do not ask any interactive question
      --env[=ENV]        The environment the command should run under
  -v|vv|vvv, --verbose   Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```
</details>

<br>

##### `php artisan make:module:view`
This command generates a blade view into a module.  
By passing in the corresponding option you can define in which module the view should be generated.
<details>
    <summary>Description / Usage / Arguments / Options</summary>

```
Description:
  Create a new blade view file in a module

Usage:
  make:module:view [options] [--] <name>

Arguments:
  name                   The name for the blade view

Options:
      --module[=MODULE]  Generate a view file in a certain module
  -h, --help             Display this help message
  -q, --quiet            Do not output any message
  -V, --version          Display this application version
      --ansi             Force ANSI output
      --no-ansi          Disable ANSI output
  -n, --no-interaction   Do not ask any interactive question
      --env[=ENV]        The environment the command should run under
  -v|vv|vvv, --verbose   Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```
</details>

<br>

##### `php artisan make:module:translation`
This command generates a translation into a module.  
By passing in the corresponding option you can define in which module the translation should be generated.
<details>
    <summary>Description / Usage / Arguments / Options</summary>

```
Description:
  Create a new translation file in a module

Usage:
  make:module:translation [options] [--] <name>

Arguments:
  name                   The language short code of the translation

Options:
      --module[=MODULE]  Generate a translation file in a certain module
  -h, --help             Display this help message
  -q, --quiet            Do not output any message
  -V, --version          Display this application version
      --ansi             Force ANSI output
      --no-ansi          Disable ANSI output
  -n, --no-interaction   Do not ask any interactive question
      --env[=ENV]        The environment the command should run under
  -v|vv|vvv, --verbose   Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```
</details>

<br>

##### `php artisan make:module:route`
This command generates route files into a module.  
By passing in options you can define in which module and what kind of route files should be generated.
<details>
    <summary>Description / Usage / Options</summary>

```
Description:
  Create a new route file in a module

Usage:
  make:module:route [options]

Options:
      --simple           Generate a simple routes.php file
      --web              Generate a web route file
      --api              Generate an api route file
      --module[=MODULE]  Generate a route file in a certain module
  -h, --help             Display this help message
  -q, --quiet            Do not output any message
  -V, --version          Display this application version
      --ansi             Force ANSI output
      --no-ansi          Disable ANSI output
  -n, --no-interaction   Do not ask any interactive question
      --env[=ENV]        The environment the command should run under
  -v|vv|vvv, --verbose   Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```
</details>

<br>

<br>

##### `php artisan make:module:migration`
This command generates a migration into a module.  
By passing in options you can define in which module it should be generated, also amongst others the table to be created.
<details>
    <summary>Description / Usage / Arguments / Options</summary>

```
Description:
  Create a new migration file in a module

Usage:
  make:module:migration [options] [--] <name>

Arguments:
  name                   The name of the migration

Options:
      --create[=CREATE]  The table to be created
      --table[=TABLE]    The table to migrate
      --module[=MODULE]  Generate a migration in a certain module
      --path[=PATH]      The location where the migration file should be created
      --fullpath         Output the full path of the migration
  -h, --help             Display this help message
  -q, --quiet            Do not output any message
  -V, --version          Display this application version
      --ansi             Force ANSI output
      --no-ansi          Disable ANSI output
  -n, --no-interaction   Do not ask any interactive question
      --env[=ENV]        The environment the command should run under
  -v|vv|vvv, --verbose   Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```
</details>

<br>

##### `php artisan make:module:seeder`
This command generates a seeder into a module.  
By passing in the corresponding option you can define in which module the seeder should be generated.
<details>
    <summary>Description / Usage / Arguments / Options</summary>

```
Description:
  Create a new seeder class in a module

Usage:
  make:module:seeder [options] [--] <name>

Arguments:
  name                   The name of the class

Options:
      --module[=MODULE]  Generate a seeder in a certain module
  -h, --help             Display this help message
  -q, --quiet            Do not output any message
  -V, --version          Display this application version
      --ansi             Force ANSI output
      --no-ansi          Disable ANSI output
  -n, --no-interaction   Do not ask any interactive question
      --env[=ENV]        The environment the command should run under
  -v|vv|vvv, --verbose   Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```
</details>

<br>

##### `php artisan make:module:factory`
This command generates a factory into a module.  
By passing in options you can define in which module it should be generated and the model.
<details>
    <summary>Description / Usage / Arguments / Options</summary>

```
Description:
  Create a new model factory in a module

Usage:
  make:module:factory [options] [--] <name>

Arguments:
  name                   The name of the class

Options:
  -m, --model[=MODEL]    The name of the model
      --module[=MODULE]  Generate a factory in a certain module
  -h, --help             Display this help message
  -q, --quiet            Do not output any message
  -V, --version          Display this application version
      --ansi             Force ANSI output
      --no-ansi          Disable ANSI output
  -n, --no-interaction   Do not ask any interactive question
      --env[=ENV]        The environment the command should run under
  -v|vv|vvv, --verbose   Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```
</details>

<br>

##### `php artisan make:module:helpers`
This command generates a helpers file into a module.  
By passing in the corresponding option you can define in which module the helpers file should be generated.
<details>
    <summary>Description / Usage / Options</summary>

```
Description:
  Create a new helpers file in a module

Usage:
  make:module:helpers [options]

Options:
      --module[=MODULE]  Generate a helpers file in a certain module
  -h, --help             Display this help message
  -q, --quiet            Do not output any message
  -V, --version          Display this application version
      --ansi             Force ANSI output
      --no-ansi          Disable ANSI output
  -n, --no-interaction   Do not ask any interactive question
      --env[=ENV]        The environment the command should run under
  -v|vv|vvv, --verbose   Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```
</details>

<br>

##### `php artisan module:list`
This command simply lists all components and their status.
<details>
    <summary>Description / Usage / Options</summary>

```
Description:
  List the application's modules

Usage:
  module:list

Options:
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
      --env[=ENV]       The environment the command should run under
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```
</details>

<br>

### Views

To tell Laravel that you want to render a view file from a specific module, you need to use the double-colon syntax.  
The `welcome.blade.php` from the example module `FooBar` could be rendered like this

```php
return view("FooBar::welcome");
```

<br>

### Translations

For the translations applies the same as for the views. You can access them with the double-colon syntax.

```php
echo trans('FooBar::example.welcome');
```

<br>

### Routing

Unless otherwise configured, the service provider will look for the files `routes/web.php` and `routes/api.php` and load them with the corresponding middleware and the controllers namespace.  
That means you can register routes without having to enter the full namespace.

```php
Route::resource('foo-bar', 'FooBarController');
```

<br>

### Migrations

Unless otherwise configured, the service provider will expect the migrations inside the `database/migrations/` folder.

<br>

### Factories

For the factories applies the same as for the migrations.  
Unless otherwise configured, the service provider will expect the factories inside the `database/factories/` folder.

<br>

### Loading additional classes

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
To be able to do the mentioned settings there must be a `config/modules.php` file which should return an array.

<br>

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

Pick either `0` to publish everything or at least the number with `Provider: ArtemSchander\L5Modular\ModuleServiceProvider`.  
When this is done, you can configure in the published `config/modules.php` file the following...

<br>

#### `'generate'`

By default the generation of some components is disabled.  
The `generate` array accepts boolean values to enable / disable the generation of a component.

```php
'generate' => [
    'controller' => true,
    'resource' => false,
    'request' => false,
    'model' => true,
    'mail' => false,
    'notification' => false,
    'event' => false,
    'listener' => false,
    'observer' => false,
    'job' => false,
    'view' => true,
    'translation' => true,
    'routes' => true,
    'migration' => false,
    'seeder' => false,
    'factory' => false,
    'helpers' => false,
],
```

<br>

#### `'default'`

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
        'controllers' => 'Http/Controllers',
        'resources' => 'Http/Resources',
        'requests' => 'Http/Requests',
        'models' => 'Models',
        'mails' => 'Mail',
        'notifications' => 'Notifications',
        'events' => 'Events',
        'listeners' => 'Listeners',
        'observers' => 'Observers',
        'jobs' => 'Jobs',
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

<br>

#### `'default.routing'`

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

<br>

#### `'default.structure'`

The structure config accepts an associative array, while the values represent the path to the component stated in the key.

```php
'structure' => [
    'controllers' => 'Http/Controllers',
    'resources' => 'Http/Resources',
    'requests' => 'Http/Requests',
    'models' => 'Models',
    'mails' => 'Mail',
    'notifications' => 'Notifications',
    'events' => 'Events',
    'listeners' => 'Listeners',
    'observers' => 'Observers',
    'jobs' => 'Jobs',
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

<br>

#### `'specific'`

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
    |         'controllers' => 'Controllers',
    |         'views' => 'Views',
    |         'translations' => 'Translations',
    |     ],
    | ],
    */

],
```

In every module specific config you can configure the `routing` and the `structure` the same way as it is possible for the default config.

<br>

#### Disable a module

To disable a module you need to set the `enabled` setting to `false`.  
The config to disable the FooBar module would then look like this

```php
'FooBar' => [
    'enabled' => false,
],
```

<br>

#### Change the routing

To change the routing to load only a simple `routes.php` for the FooBar module you would need this config

```php
'FooBar' => [
    'routing' => [ 'simple' ],
],
```

<br>

#### Change the structure

You can completely customize the structure of each module.

```php
'FooBar' => [
    'routing' => [ 'simple' ],
    'structure' => [
        'controllers' => 'Controllers',
        'resources' => 'Resources',
        'requests' => 'Requests',
        'models' => 'Entities',
        'views' => 'Views',
        'translations' => 'Translations',
        'routes' => '',
        'migrations' => 'database/migrations',
        'seeds' => 'database/seeds',
        'factories' => 'database/factories',
        'helpers' => '',
    ],
],
```

With this config the service provider would expect the following structure and load all existing files while ignoring the nonexistent ones

```bash
laravel-project/
    app/
    └── Modules/
        └── FooBar/
            ├── Controllers
            │   └── FooBarController.php
            ├── Entities
            │   └── FooBar.php
            ├── Resources
            │   └── FooBarResource.php
            ├── Requests
            │   └── FooBarRequest.php
            ├── Translations
            │   └── en.php
            ├── Views
            │   └── index.blade.php
            ├── database
            │   ├── factories
            │   │   └── FooBarFactory.php
            │   ├── migrations
            │   │   └── xxx_create_foo_bars_table.php
            │   └── seeds
            │       └── FooBarSeeder.php
            ├── helpers.php
            └── routes.php
```

<br>
<br>

---

## Contributors

Thanks goes to these wonderful people:
<!-- ALL-CONTRIBUTORS-LIST:START - Do not remove or modify this section -->
<!-- prettier-ignore-start -->
<!-- markdownlint-disable -->
<table>
  <tr>
    <td align="center"><a href="https://github.com/juliomotol"><img src="https://avatars0.githubusercontent.com/u/21353103?v=4" width="100px;" alt=""/><br /><sub><b>Julio Motol</b></sub></a><br /><a href="https://github.com/Artem-Schander/L5Modular/commits?author=juliomotol" title="Code">💻</a> <a href="https://github.com/Artem-Schander/L5Modular/commits?author=juliomotol" title="Tests">⚠️</a> <a href="#tool-juliomotol" title="Tools">🔧</a></td>
    <td align="center"><a href="http://alpin11.at"><img src="https://avatars3.githubusercontent.com/u/24294584?v=4" width="100px;" alt=""/><br /><sub><b>David Höck </b></sub></a><br /><a href="https://github.com/Artem-Schander/L5Modular/commits?author=davidhoeck" title="Code">💻</a></td>
    <td align="center"><a href="https://github.com/mrpamacs"><img src="https://avatars0.githubusercontent.com/u/1251025?v=4" width="100px;" alt=""/><br /><sub><b>Kis Viktor</b></sub></a><br /><a href="https://github.com/Artem-Schander/L5Modular/commits?author=mrpamacs" title="Code">💻</a></td>
    <td align="center"><a href="https://github.com/AkramBerkawy"><img src="https://avatars1.githubusercontent.com/u/3511510?v=4" width="100px;" alt=""/><br /><sub><b>Akram Berkawy</b></sub></a><br /><a href="https://github.com/Artem-Schander/L5Modular/commits?author=AkramBerkawy" title="Code">💻</a></td>
    <td align="center"><a href="https://www.cideator.com"><img src="https://avatars0.githubusercontent.com/u/241825?v=4" width="100px;" alt=""/><br /><sub><b>Farhan Wazir</b></sub></a><br /><a href="https://github.com/Artem-Schander/L5Modular/commits?author=farhanwazir" title="Code">💻</a></td>
    <td align="center"><a href="https://github.com/tombombadilll"><img src="https://avatars2.githubusercontent.com/u/1056064?v=4" width="100px;" alt=""/><br /><sub><b>Thomas Eriksson</b></sub></a><br /><a href="https://github.com/Artem-Schander/L5Modular/issues?q=author%3Atombombadilll" title="Bug reports">🐛</a></td>
  </tr>
</table>

<!-- markdownlint-enable -->
<!-- prettier-ignore-end -->
<!-- ALL-CONTRIBUTORS-LIST:END -->

Also thanks to zyhn for the ["Modular Structure in Laravel 5" tutorial](http://ziyahanalbeniz.blogspot.com.tr/2015/03/modular-structure-in-laravel-5.html). Well explained and helped a lot.

<br>
<br>

---

## License

L5Modular is licensed under the terms of the [MIT License](https://github.com/Artem-Schander/L5Modular/blob/master/LICENSE)
(See LICENSE file for details).
