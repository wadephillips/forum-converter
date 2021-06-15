<?php

namespace wadelphillips\ForumConverter;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use wadelphillips\ForumConverter\Commands\ForumConverterCommand;

class ForumConverterServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('forum-converter')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_forum_converter_table')
            ->hasCommand(ForumConverterCommand::class);
    }

    public function packageRegistered()
    {
        config()->set('database.connections.legacy',
            config('forum-converter.database.connections.legacy'));

    }
}
