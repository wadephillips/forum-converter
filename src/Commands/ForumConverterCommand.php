<?php

namespace wadephillips\ForumConverter\Commands;

use Carbon\CarbonInterval;
use Illuminate\Console\Command;
use function time;
use wadephillips\ForumConverter\Converters\Category;
use wadephillips\ForumConverter\Converters\Comment;
use wadephillips\ForumConverter\Converters\Forum;
use wadephillips\ForumConverter\Converters\ForumAttachment;
use wadephillips\ForumConverter\Converters\Topic;
use wadephillips\ForumConverter\Models\LegacyCategory;
use wadephillips\ForumConverter\Models\LegacyComment;
use wadephillips\ForumConverter\Models\LegacyForum;
use wadephillips\ForumConverter\Models\LegacyForumAttachment;
use wadephillips\ForumConverter\Models\LegacyTopic;

class ForumConverterCommand extends Command
{
    private int $startTime;

    private int $endTime;

    public $signature = 'ee-forum:migrate
                        {--A|all : Migrate all categories, forums, topics, and comments at one time }
                        {--C|categories : Migrate the categories only}
                        {--f|forums : Migrate the forums only}
                        {--t|topics : Migrate the topics only}
                        {--c|comments : Migrate the comments only}
                        {--a|attachments : Migrate the attachments only}
                        {--l|limit=5 : Limit the number of components to migrate}';
    public $description = 'Migrates ExpressionEngine 2 forum components into a Wordpress structure suitable for use with BBPress or Buddy Boss';

    public function handle()
    {
        $this->startTime = time();
        $options = $this->options();

        if ($options[ 'all' ] && $this->confirm('Are you sure you want to migrate all categories, forums, topics, comments, and attachments at one time??  This could take a while....')) {
            $this->startTime = time();

            $this->newLine(1);
            $this->info('Migrating All Forum Components...');

            $this->migrateAllComponents();
        }

        if ($options[ 'categories' ]) {
            $this->migrateCategories();
        }

        if ($options[ 'forums' ]) {
            $this->migrateForums();
        }

        if ($options[ 'topics' ]) {
            $this->migrateTopics();
            $this->newLine(1);
        }

        if ($options[ 'comments' ]) {
            $this->migrateComments();
        }

        if ($options[ 'attachments' ]) {
            $this->migrateAttachments();
        }

        $this->endTime = time();

        $this->newLine(2);
        $this->info('All done in ' . $this->getElapsedTimeString($this->startTime, $this->endTime));
    }

    private function migrateAllComponents()
    {
        $this->migrateCategories();
        $this->migrateForums();
        $this->migrateTopics();
        $this->migrateComments();
        $this->migrateAttachments();
    }

    private function migrateCategories()
    {
        $this->newLine(1);
        $this->info('Migrating Forum Categories...');

        return $this->migrate(LegacyCategory::class, Category::class);
    }

    private function migrateForums()
    {
        $this->newLine(1);
        $this->info('Migrating Forums...');

        return $this->migrate(LegacyForum::class, Forum::class);
    }

    private function migrateTopics()
    {
        $this->newLine(1);
        $this->info('Migrating Forum Topics...');

        return $this->migrate(LegacyTopic::class, Topic::class);
    }

    private function migrateComments()
    {
        $this->newLine(1);
        $this->info('Migrating Forum Comments...');

        return $this->migrate(LegacyComment::class, Comment::class);
    }

    private function migrateAttachments()
    {
        $this->newLine(1);
        $this->info('Migrating Forum Attachments...');

        return $this->migrate(LegacyForumAttachment::class, ForumAttachment::class);
    }

    private function migrate($from, $converter)
    {
        $start = time();
        //get the items that we need to convert
        $items = $this->withProgressBar($from::all(), function ($item) use ($converter) {
            // and then convert them into the new type
            return $converter::migrate($item);
        });
        $end = time();

        $this->newLine();
        $this->info('... in ' . $this->getElapsedTimeString($start, $end));

        return $items;
    }

    private function getElapsedTimeString(int $start, int $end)
    {
        $elapsed = $end - $start;

        return CarbonInterval::seconds($elapsed)->cascade()->forHumans();
    }
}
