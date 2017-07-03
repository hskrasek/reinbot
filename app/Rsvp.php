<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rsvp extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'response',
    ];

    protected $casts = [
        'response' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
