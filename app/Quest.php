<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Quest
 *
 * @property int $id
 * @property array $json
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Quest byBungieId($id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Quest whereBungieIdIn($ids)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Quest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Quest whereJson($value)
 * @mixin \Eloquent
 */
class Quest extends Model
{
    protected $table = 'DestinyInventoryItemDefinition';

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

    public function scopeWhereBungieIdIn($query, array $ids)
    {
        return $query->whereIn('id', $ids)->orWhereIn('id', array_map(function ($id) {
            return $id - 4294967296;
        }, $ids));
    }
}
