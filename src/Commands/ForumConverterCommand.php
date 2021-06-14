<?php

namespace wadelphillips\ForumConverter\Commands;

use Illuminate\Console\Command;
use function collect;

class ForumConverterCommand extends Command
{
    public $signature = 'ee-forum:migrate
                        {--a|all : Migrate all categories, forums, topics, and comments at one time }
                        {--C|categories : Migrate the categories only}
                        {--f|forums : Migrate the forums only}
                        {--t|topics : Migrate the topics only}
                        {--c|comments : Migrate the comments only}';

    public $description = 'Migrates ExpressionEngine 2 forum components into a Wordpress structure suitable for use with BBPress or Buddy Boss';

    public function handle()
    {
        $arguments = $this->arguments();
        $options = $this->options();

        if ($options['all']) {
            $this->info('Migrating All Forum Components');
        }

        if ($options['categories']) {
            $this->info('Migrating Forum Categories');
        }

        if ($options['forums']) {
            $this->info('Migrating Forums');
        }

        if ($options['topics']) {
            $this->info('Migrating Forum Topics');
        }

        if ($options['comments']) {
            $this->info('Migrating Forum Comments');
        }

        $this->comment('All done');
    }
}
