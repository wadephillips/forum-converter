<?php

namespace wadelphillips\ForumConverter\Commands;

use Illuminate\Console\Command;
use function implode;
use function is_null;

use const PHP_EOL;
use wadelphillips\ForumConverter\Services\PseudoTagReplacer;

use wadelphillips\ForumConverter\Services\TagReplacer;

class ParseLegacyTopicFormattingCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ee-forum:parse-format 
        { model : The model that should be used. }
        {--dry-run : Show the results of reformatting the topic(s) but do not save changes}
        { --id=0 : The id of a specific topic to be updated}
        { --limit= : Limit the number of records to be updated}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up legacy formatting from EE Forums in new wp forums';

    /**
     * @var PseudoTagReplacer
     */
    private PseudoTagReplacer $replacer;

    private TagReplacer $tagReplacer;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->tagReplacer = new TagReplacer();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $model = implode('', ["wadelphillips\ForumConverter\Models", '\\', $this->argument('model')]);
        //get a set of topics/comments to parse
        if ($this->option('id') > 0) {
            $topicsQuery = $model::where('ID', $this->option('id'));
        } else {
            $topicsQuery = $model::query()->where('post_content', 'LIKE', '%[%]%');
        }

        if (! is_null($this->options('limit'))) {
            $topicsQuery->limit($this->option('limit'));
        }

        $topics = $topicsQuery->get();

        if ($topics->count() == 0) {
            $this->alert('There are no topics to update!');

            return 0;
        }
        $this->info('Lets parse some strings!');


        $this->info('Replacing tags');
        $bar = $this->output->createProgressBar($topics->count());
        $bar->start();
        // create a look up array of pseudo tags we need to look for and their replacements from a file
        //for each item we should search the the body for any of our pseudo tags
        foreach ($topics as $topic) {
            //when we find instances of our bad tags we need to replace them with proper html,
            // basic tags like [b] can be replaced directly with html like <b>.  But
            $body = $this->tagReplacer->reformatSimpleTags($topic->post_content);

            // More complicated tags will need to be manipulated [url=http://shoes.com] => <a href='http://shoes.com'>
            $body = $this->tagReplacer->reformatComplexTags($body);
            $topic->post_content = $body;

            if (! $this->option('dry-run')) {
                $topic->save();
            } else {
                dump($topic->ID, $topic->body);
            }
            $bar->advance();
        }
        $bar->finish();
        $this->line(PHP_EOL);
        $this->info('Your strings have been updated!');

        return 0;
    }
}
