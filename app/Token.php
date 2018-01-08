<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Token
 *
 * @property int $id
 * @property string $access_token
 * @property string $refresh_token
 * @property string $type
 * @property \Carbon\Carbon|null $expires_in
 * @property \Carbon\Carbon|null $refresh_expires_in
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Token whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Token whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Token whereExpiresIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Token whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Token whereRefreshExpiresIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Token whereRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Token whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Token whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Token extends Model
{
    protected $fillable = [
        'access_token',
        'refresh_token',
        'type',
        'expires_in',
        'refresh_expires_in',
    ];

    protected $dates = [
        'expires_in',
        'refresh_expires_in',
    ];
}
