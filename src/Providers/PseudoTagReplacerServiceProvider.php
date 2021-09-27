<?php

namespace wadelphillips\ForumConverter\Providers;

use Illuminate\Support\ServiceProvider;
use wadelphillips\ForumConverter\Services\PseudoTagReplacer;

class PseudoTagReplacerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PseudoTagReplacer::class, function ($app) {
            return new PseudoTagReplacer();
        });
    }
}
