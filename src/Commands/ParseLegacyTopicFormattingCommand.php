<?php

namespace wadelphillips\ForumConverter\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use function implode;
use function info;
use function is_null;

use function sprintf;
use const PHP_EOL;
use wadelphillips\ForumConverter\Services\PseudoTagReplacer;

use wadelphillips\ForumConverter\Services\TagReplacer;

class ParseLegacyTopicFormattingCommand extends Command
{

    /**
     * This command searches through WP Posts and replaces the pseudo html markup from
     * ExpressionEngine 2 forums
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
        //set the fully qualified name of the model to be searched.
        //  todo create a namespace option so that this could be extended and changed at runtime
        $argumentModel = $this->argument('model');
        $model = implode('',
                         [
                             "wadelphillips\ForumConverter\Models",
                             '\\',
                             $argumentModel,
                         ]
        );

        //get a set of entries to parse
        if ($this->option('id') > 0) {
            $modelQuery = $model::where('ID', $this->option('id'));
        } else {
            $modelQuery = $model::query()->where('post_content', 'LIKE', '%[%]%');
        }

        if (! is_null($this->options('limit'))) {
            $modelQuery->limit($this->option('limit'));
        }

        $models = $modelQuery->get();

        if ($models->count() == 0) {
            $this->alert(sprintf('There are no %s to update!', Str::plural($argumentModel)));

            return 0;
        }

        $this->info(sprintf('Lets parse some %s!', Str::plural($argumentModel)));


        $this->info('Replacing tags');

        // Start progress bar
        $bar = $this->output->createProgressBar($models->count());
        $bar->start();

        // Scan each format
        foreach ($models as $topic) {
            //when we find instances of our bad tags we need to replace them with proper html,
            // basic tags like [b] can be replaced directly with html like <b>.  But
            $body = $this->tagReplacer->reformatSimpleTags($topic->post_content);

            // More complicated tags will need to be manipulated [url=http://shoes.com] => <a href='http://shoes.com'>
            $body = $this->tagReplacer->reformatComplexTags($body);
            $topic->post_content = $body;

            if (! $this->option('dry-run')) {
                $topic->save();
            } else {
                $this->info($topic->ID);
                $this->newLine();
                $this->info($topic->post_content);
                $this->newLine();
                $this->line('---------------------');
                $this->newLine();
            }
            $bar->advance();
        }
        // close out progress bar and finish up command
        $bar->finish();
        $this->newLine();
        unset($models);
        $this->info('Your strings have been updated!');

        return 0;
    }
}
