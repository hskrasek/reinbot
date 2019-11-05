<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Plan
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $response_url
 * @property string|null $message_ts
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $scheduled_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Rsvp[] $rsvps
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereMessageTs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereResponseUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereScheduledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereUserId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan query()
 */
class Plan extends Model
{
    protected $fillable = [
        'scheduled_at',
        'user_id',
        'response_url',
        'message_ts',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'scheduled_at',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function rsvps()
    {
        return $this->hasMany(Rsvp::class);
    }

    public function hasStarted()
    {
        return $this->scheduled_at->isPast();
    }
}
