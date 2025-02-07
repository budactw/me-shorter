<?php

namespace App\Providers;

use App\Util\ShortUrlUtil;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class ShortUrlProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('facade.short-url', function (Application $app) {
            return new ShortUrlUtil();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
