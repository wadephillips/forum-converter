<?php

namespace wadelphillips\ForumConverter\Commands;

use Illuminate\Console\Command;

class ForumConverterCommand extends Command
{
    public $signature = 'ee2-forum-to-bbp';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
