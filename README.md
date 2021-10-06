# Migrate Expression Engine 2 Forums into BBPress/ BuddyBoss forums.


---
This package can be used to migrate Expression Engine 2x forums to BBpress/ BuddyBoss Forums on WordPress. 

It provides two artisan commands:

```
ee-forum:migrate
```

and

```
ee-forum:parse-format
```

The `ee-forum:migrate` command migrates forum categories, threads, comments, and attachments from an Expression Engine forum instance to a BBPress/BuddyBoss instance.

The `ee-forum:parse-format` command replaces ExpressionEngine specific formatting with html tags.


## Installation

You can install the package via composer:

```bash
composer require wadelphillips/forum-converter
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="wadephillips\ForumConverter\ForumConverterServiceProvider" --tag="forum-converter-migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="wadelphillips\ForumConverter\ForumConverterServiceProvider" --tag="forum-converter-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$forum-converter = new wadelphillips\ForumConverter();
echo $forum-converter->echoPhrase('Hello, Spatie!');
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

- [Wade Phillips](https://github.com/wadelphillips)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
