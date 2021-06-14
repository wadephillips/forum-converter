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
            ->name('ee2-forum-to-bbp')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_ee2-forum-to-bbp_table')
            ->hasCommand(ForumConverterCommand::class);
    }
}
