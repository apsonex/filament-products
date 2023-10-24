# Apsonex/Filament-Products

[![Latest Version on Packagist](https://img.shields.io/packagist/v/apsonex/filament-products.svg?style=flat-square)](https://packagist.org/packages/apsonex/filament-products)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/apsonex/filament-products/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/apsonex/filament-products/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/apsonex/filament-products/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/apsonex/filament-products/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/apsonex/filament-products.svg?style=flat-square)](https://packagist.org/packages/apsonex/filament-products)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require apsonex/filament-products
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-products-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-products-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-products-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$filamentProducts = new Apsonex\FilamentProducts();
echo $filamentProducts->echoPhrase('Hello, Apsonex!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Gurinder Chauhan](https://github.com/apsonex)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
