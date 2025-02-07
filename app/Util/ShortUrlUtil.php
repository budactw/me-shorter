<?php

namespace App\Util;

use Hidehalo\Nanoid\Client;

class ShortUrlUtil
{
    public function generate(?int $length = null): string
    {
        $client = new Client();

        return $client->generateId($length ?? rand(8, 10));
    }

}