<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Plan
 *
 * @property int                                                       $id
 * @property int                                                       $user_id
 * @property \Carbon\Carbon                                            $scheduled_at
 * @property string                                                    $response_url
 * @property string                                                    $message_ts
 * @property \Carbon\Carbon|null                                       $created_at
 * @property \Carbon\Carbon|null                                       $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Rsvp[] $rsvps
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereResponseUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereMessageTs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereScheduledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereUserId($value)
 * @mixin \Eloquent
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

    public function rsvps()
    {
        return $this->hasMany(Rsvp::class);
    }
}
