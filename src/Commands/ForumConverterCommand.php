<?php

namespace wadelphillips\ForumConverter\Commands;

use Illuminate\Console\Command;
use wadelphillips\ForumConverter\Converters\Category;
use wadelphillips\ForumConverter\Models\LegacyCategory;


class ForumConverterCommand extends Command
{
    public $signature = 'ee-forum:migrate
                        {--a|all : Migrate all categories, forums, topics, and comments at one time }
                        {--C|categories : Migrate the categories only}
                        {--f|forums : Migrate the forums only}
                        {--t|topics : Migrate the topics only}
                        {--c|comments : Migrate the comments only}
                        {--l|limit=5 : Limit the number of components to migrate';

    public $description = 'Migrates ExpressionEngine 2 forum components into a Wordpress structure suitable for use with BBPress or Buddy Boss';

    public function handle()
    {
        $options = $this->options();

        if ( $options[ 'all' ] ) {
            $this->info('Migrating All Forum Components...');
            //todo: add a class to handle this option, new Components::migrate
            $this->migrateComponents();
        }

        if ( $options[ 'categories' ] ) {
            $this->info('Migrating Forum Categories...');
            //todo: add a class to handle this option, new Categories::migrate
            $this->migrateCategories();
        }

        if ( $options[ 'forums' ] ) {
            $this->info('Migrating Forums...');
            //todo: add a class to handle this option, new Forums::migrate
            $this->migrateForums();
        }

        if ( $options[ 'topics' ] ) {
            $this->info('Migrating Forum Topics...');
            //todo: add a class to handle this option, new Topics::migrate
            $this->migrateTopics();
        }

        if ( $options[ 'comments' ] ) {
            $this->info('Migrating Forum Comments...');
            //todo: add a class to handle this option, new Comments::migrate
            $this->migrateComments();
        }

        $this->comment('All done');
    }

    private function migrateComponents()
    {
        $this->migrateCategories();
        $this->migrateForums();
        $this->migrateTopics();
        $this->migrateComments();
        //todo what should we return?
    }

    private function migrateCategories()
    {

        // We need to get the appropriate set of legacy items
        $items = LegacyCategory::all()->each(function ($item) {
            //$options = [];
            // and then convert them into the new type
            return Category::migrate($item);
        });
    }

    private function migrateForums()
    {
    }

    private function migrateTopics()
    {
        //need to chunk the conversions
    }

    private function migrateComments()
    {
        //need to chunk the conversions
    }
}
