# Difftastic PHP

This Composer package provides a wrapper around [difftastic](https://github.com/Wilfred/difftastic) 
for usage in PHP based projects; therefor it requires `difftastic` to be [installed](https://difftastic.wilfred.me.uk/installation.html).

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
