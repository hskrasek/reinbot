<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Manifest
 *
 * @property int $id
 * @property string $version
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Manifest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Manifest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Manifest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Manifest whereVersion($value)
 * @mixin \Eloquent
 */
class Manifest extends Model
{
    protected $fillable = [
        'version',
    ];
}
