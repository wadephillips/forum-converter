<?php

namespace wadelphillips\ForumConverter\Tests\Commands;

use wadelphillips\ForumConverter\Commands\ForumConverterCommand;
use wadelphillips\ForumConverter\Tests\TestCase;


class ForumConverterCommandTest extends TestCase
{
    /** @test */
    public function the_forum_converter_command_works()
    {
        //arrange

        //act
        $this->artisan('ee-forum:migrate')->assertExitCode(0);
        //assert
    }

}
