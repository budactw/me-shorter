<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShortUrl extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'original_url',
        'expired_at',
        'delete_code',
        'short_code',
        'clicks',
    ];

    public function getRouteKeyName(): string
    {
        return 'short_code';
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->whereRaw('BINARY short_code = ?', [$value])->firstOrFail();
    }

    public function recordClick(): void
    {
        $this->increment('clicks');
    }
}
