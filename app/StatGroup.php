<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\StatGroup
 *
 * @property int $id
 * @property array $json
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatGroup byBungieId($id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatGroup whereJson($value)
 * @mixin \Eloquent
 */
class StatGroup extends Model
{
    protected $table = 'DestinyStatGroupDefinition';

    protected $connection = 'destiny_manifest';

    protected $casts = [
        'json' => 'json',
    ];

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $id
     */
    public function scopeByBungieId($query, $id)
    {
        return $query->where('id', $id)->orWhere('id', $id - 4294967296);
    }
}
