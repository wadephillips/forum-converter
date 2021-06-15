<?php

namespace wadelphillips\ForumConverter\Commands;

use Illuminate\Console\Command;

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

        $options = $this->options();

        if ($options['all']) {
            $this->info('Migrating All Forum Components');
            //todo: add a class to handle this option, new Components::migrate
        }

        if ($options['categories']) {
            $this->info('Migrating Forum Categories');
            //todo: add a class to handle this option, new Categories::migrate
        }

        if ($options['forums']) {
            $this->info('Migrating Forums');
            //todo: add a class to handle this option, new Forums::migrate
        }

        if ($options['topics']) {
            $this->info('Migrating Forum Topics');
            //todo: add a class to handle this option, new Topics::migrate
        }

        if ($options['comments']) {
            $this->info('Migrating Forum Comments');
            //todo: add a class to handle this option, new Comments::migrate
        }

        $this->comment('All done');
    }
}
