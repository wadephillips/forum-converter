<?php

namespace wadelphillips\ForumConverter\Tests\Commands;

use wadelphillips\ForumConverter\Commands\ForumConverterCommand;
use wadelphillips\ForumConverter\Tests\TestCase;


class ForumConverterCommandTest extends TestCase
{
    /** @test */
    public function the_forum_converter_command_works()
    {
        $this->artisan('ee-forum:migrate')->assertExitCode(0);
    }

    /** @test */
    public function it_accepts_a_categories_flag()
    {

        $this->artisan('ee-forum:migrate --categories')
            ->expectsOutput('Migrating Forum Categories')
            ->assertExitCode(0);


        $this->artisan('ee-forum:migrate -C')
            ->expectsOutput('Migrating Forum Categories')
            ->assertExitCode(0);

    }

    /** @test */
    public function it_accepts_a_forums_flag()
    {

        $this->artisan('ee-forum:migrate --forums')
            ->expectsOutput('Migrating Forums')
            ->assertExitCode(0);


        $this->artisan('ee-forum:migrate -f')
            ->expectsOutput('Migrating Forums')
            ->assertExitCode(0);

    }

    /** @test */
    public function it_accepts_a_topics_flag()
    {

        $this->artisan('ee-forum:migrate --topics')
            ->expectsOutput('Migrating Forum Topics')
            ->assertExitCode(0);


        $this->artisan('ee-forum:migrate -t')
            ->expectsOutput('Migrating Forum Topics')
            ->assertExitCode(0);

    }

    /** @test */
    public function it_accepts_a_all_flag()
    {

        $this->artisan('ee-forum:migrate --comments')
            ->expectsOutput('Migrating Forum Comments')
            ->assertExitCode(0);


        $this->artisan('ee-forum:migrate -c')
            ->expectsOutput('Migrating Forum Comments')
            ->assertExitCode(0);

    }

    /** @test */
    public function it_accepts_an_all_flag()
    {

        $this->artisan('ee-forum:migrate --all')
            ->expectsOutput('Migrating All Forum Components')
            ->assertExitCode(0);


        $this->artisan('ee-forum:migrate -a')
            ->expectsOutput('Migrating All Forum Components')
            ->assertExitCode(0);

    }

}
