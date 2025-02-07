<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $short_code
 * @property string $original_url
 * @property string $delete_code
 * @property string|null $expired_at
 * @property int $clicks
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShortUrl newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShortUrl newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShortUrl query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShortUrl whereClicks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShortUrl whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShortUrl whereDeleteCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShortUrl whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShortUrl whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShortUrl whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShortUrl whereOriginalUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShortUrl whereShortCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShortUrl whereUpdatedAt($value)
 */
	class ShortUrl extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

