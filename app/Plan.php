<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'scheduled_at',
        'user_id',
        'response_url'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'scheduled_at'
    ];

    public function rsvps()
    {
        return $this->hasMany(Rsvp::class);
    }
}
