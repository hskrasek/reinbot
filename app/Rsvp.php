<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Rsvp
 *
 * @property int $user_id
 * @property int $plan_id
 * @property bool $response
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rsvp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rsvp wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rsvp whereResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rsvp whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rsvp whereUserId($value)
 * @mixin \Eloquent
 */
class Rsvp extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'response',
    ];

    protected $casts = [
        'response' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
