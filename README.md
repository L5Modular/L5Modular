# L5Modular
[![Laravel](https://img.shields.io/badge/laravel-5-orange.svg?style=flat-square)](http://laravel.com)
[![Source](https://img.shields.io/badge/source-Artem_Schander-blue.svg?style=flat-square)](https://github.com/Artem-Schander/L5Modular)
[![Release](https://img.shields.io/github/release/qubyte/rubidium.svg?style=flat-square)](https://github.com/Artem-Schander/L5Modular/releases)
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)

This Package extends the regular MVC structure from Laravel 5 to a simple modular HMVC.


## Documentation

* [Installation](#installation)
* [Getting started](#getting-started)
* [Usage](#usage)
* [Further information](#further-information)
* [Contributing](#contributing)


<a name="installation"></a>
## Installation

The best way to install this package is through your terminal via Composer:

```
composer require artem-schander/l5-modular
```
or
```
composer require artem-schander/l5-modular
```

Once this operation is complete, simply add both the service provider and facade classes to your project's `config/app.php` file:

#### Service Provider
```
'Caffeinated\Modules\ModulesServiceProvider',
```

#### Facade
```
'Module' => 'Caffeinated\Modules\Facades\Module',
```

The built in Artisan generator creates ready to use modules.
..could look like the following folder pattern:


Documentation
-------------
Coming Soon ...
