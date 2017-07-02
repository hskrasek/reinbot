<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'slack_user_id', 'timezone',
    ];

    public function plans()
    {
        return $this->hasMany(Plan::class);
    }

    public function getMentionAttribute()
    {
        return "@{$this->username}";
    }
}
