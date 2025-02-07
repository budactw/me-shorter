<?php

namespace App\Util;

use Illuminate\Support\Facades\Facade;

/**
 * @see ShortUrlUtil
 * @method static string generate(?int $length = null)
 */
class ShortUrlMaker extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'facade.short-url';
    }
}