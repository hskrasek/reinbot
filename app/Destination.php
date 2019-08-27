<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Destination
 *
 * @property int $id
 * @property array $json
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Destination byBungieId($id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Destination whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Destination whereJson($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Destination newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Destination newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Destination query()
 */
class Destination extends Model
{
    protected $table = 'DestinyDestinationDefinition';

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
