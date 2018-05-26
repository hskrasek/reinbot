<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Lore
 *
 * @property int $id
 * @property array $json
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Lore byBungieId($id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Lore whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Lore whereJson($value)
 * @mixin \Eloquent
 */
class Lore extends Model
{
    protected $table = 'DestinyLoreDefinition';

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
