<?php

namespace wadelphillips\ForumConverter\Commands;

use Illuminate\Console\Command;
use wadelphillips\ForumConverter\Converters\Category;
use wadelphillips\ForumConverter\Converters\Forum;
use wadelphillips\ForumConverter\Converters\Topic;
//use wadelphillips\ForumConverter\Converters\Comment;
use wadelphillips\ForumConverter\Models\LegacyCategory;
use wadelphillips\ForumConverter\Models\LegacyComment;
use wadelphillips\ForumConverter\Models\LegacyForum;
use wadelphillips\ForumConverter\Models\LegacyTopic;

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

        if ($options[ 'all' ]) {
            $this->newLine(1);
            $this->info('Migrating All Forum Components...');

            //todo: add a class to handle this option, new Components::migrate
            $this->migrateAllComponents();
            $this->newLine(1);
        }

        if ($options[ 'categories' ]) {
            $this->newLine(1);
            $this->info('Migrating Forum Categories...');

            //todo: add a class to handle this option, new Categories::migrate
            $this->migrateCategories();
            $this->newLine(1);
        }

        if ($options[ 'forums' ]) {
            $this->newLine(1);
            $this->info('Migrating Forums...');

            //todo: add a class to handle this option, new Forums::migrate
            $this->migrateForums();
            $this->newLine(1);
        }

        if ($options[ 'topics' ]) {
            $this->newLine(1);
            $this->info('Migrating Forum Topics...');
            //todo: add a class to handle this option, new Topics::migrate
            $this->migrateTopics();
            $this->newLine(1);
        }

        if ($options[ 'comments' ]) {
            $this->newLine(1);
            $this->info('Migrating Forum Comments...');
            //todo: add a class to handle this option, new Comments::migrate
            $this->migrateComments();
            $this->newLine(1);
        }
        $this->newLine(2);
        $this->comment('All done');
    }

    private function migrateAllComponents()
    {
        $this->migrateCategories();
        $this->migrateForums();
        $this->migrateTopics();
        $this->migrateComments();
        //todo what should we return?
    }

    private function migrateCategories()
    {
        return $this->migrate(LegacyCategory::class, Category::class);
    }

    private function migrateForums()
    {
        return $this->migrate(LegacyForum::class, Forum::class);
    }

    private function migrateTopics()
    {
        return $this->migrate(LegacyTopic::class, Topic::class);
    }

    private function migrateComments()
    {
        return $this->migrate(LegacyComment::class, Comment::class);
    }

    private function migrate($from, $converter)
    {
        //get the items that we need to convert
        $items = $this->withProgressBar($from::all(), function ($item) use ($converter) {
            // and then convert them into the new type
            return $converter::migrate($item);
        });

        return $items;
    }
}
