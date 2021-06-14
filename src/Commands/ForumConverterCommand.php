<?php

namespace wadelphillips\ForumConverter\Commands;

use Illuminate\Console\Command;

class ForumConverterCommand extends Command
{
    public $signature = 'ee-forum:migrate';

    public $description = 'Migrates ExpressionEngine 2 forum components into a Wordpress structure sutible for use with BBpress or Buddy Boss';

    public function handle()
    {
        $this->comment('All done');
    }
}
