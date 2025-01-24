# Difftastic PHP

![Test Status](https://github.com/raphaelstolt/difftastic-php/workflows/test/badge.svg)
[![Version](http://img.shields.io/packagist/v/stolt/difftastic-php.svg?style=flat)](https://packagist.org/packages/stolt/difftastic-php)
![PHP Version](https://img.shields.io/badge/php-8.1+-ff69b4.svg)
[![PDS Skeleton](https://img.shields.io/badge/pds-skeleton-blue.svg?style=flat)](https://github.com/php-pds/skeleton)

This Composer package provides a wrapper around [difftastic](https://github.com/Wilfred/difftastic) 
for usage in PHP based projects; therefor it requires `difftastic` to be [installed](https://difftastic.wilfred.me.uk/installation.html). 
Which can be done on macOS via a simple `brew install difftastic`. 

## Installation

The difftastic wrapper for PHP can be installed through Composer.

``` bash
composer require stolt/difftastic-php
```

## Usage

```php
use Stolt\Difftastic;

$difftastic = new Difftastic();
$diff = $difftastic->diff('[1, 2, 3]', '[3, 2, 1]');
```

With options differing from the default:

```php
use Stolt\Difftastic;

$difftastic = new Difftastic(background: 'light', color: 'never');
$diff = $difftastic->diff('[1, 2, 3]', '[3, 2, 1]');
```

### Running tests

``` bash
composer test
```

### License

This PHP package is licensed under the MIT license. Please see [LICENSE.md](LICENSE.md) for more details.

### Changelog

Please see [CHANGELOG.md](CHANGELOG.md) for more details.

### Contributing

Please see [CONTRIBUTING.md](.github/CONTRIBUTING.md) for more details.
