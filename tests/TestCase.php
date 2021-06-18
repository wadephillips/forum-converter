<?php

namespace wadelphillips\ForumConverter\Tests;

use function array_filter;
use function env;
use function extension_loaded;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use PDO;
use wadelphillips\ForumConverter\ForumConverterServiceProvider;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'wadelphillips\\ForumConverter\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            ForumConverterServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.legacy', [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('LEGACY_DB_HOST', '127.0.0.1'),
            'port' => env('LEGACY_DB_PORT', '3306'),
            'database' => env('LEGACY_DB_DATABASE', 'forge'),
            'username' => env('LEGACY_DB_USERNAME', 'forge'),
            'password' => env('LEGACY_DB_PASSWORD', ''),
            'unix_socket' => env('LEGACY_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => env('LEGACY_DB_PREFIX', ''),
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ]);

        /*
        include_once __DIR__.'/../database/migrations/create_forum-converter_table.php.stub';
        (new \CreatePackageTable())->up();
        */
    }
}
