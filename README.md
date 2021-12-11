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
composer require wadephillips/forum-converter
```

You can publish the config files with:
```bash
php artisan vendor:publish --provider="wadephillips\ForumConverter\ForumConverterServiceProvider" --tag="forum-converter-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage
In your terminal enter either of these two artisan commands
```bash
php artisan ee-forum:migrate
#
php artisan ee-forum:parse-format
```

## ENV
You'll need to define the following variables in your .env file
```bash
LEGACY_DB_HOST
LEGACY_DB_PORT
LEGACY_DB_DATABASE
LEGACY_DB_USERNAME
LEGACY_DB_PASSWORD
LEGACY_DB_SOCKET
# Use absolute paths for these attachment paths
LEGACY_FORUM_ATTACHMENTS_PATH
WP_FORUM_ATTACHMENTS_PATH
```

## Credits

- [Wade Phillips](https://github.com/wadephillips)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
