<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\InventoryItem
 *
 * @property int $id
 * @property array $json
 * @property CharacterClass $class
 * @property Lore $lore
 * @property \Illuminate\Support\Collection|Stat[] $stats
 * @property \Illuminate\Support\Collection|InventoryItem[] $sockets
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InventoryItem byBungieId($id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InventoryItem whereBungieIdIn($ids)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InventoryItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InventoryItem whereJson($value)
 * @mixin \Eloquent
 */
class InventoryItem extends Model
{
    protected $table = 'DestinyInventoryItemDefinition';

    protected $connection = 'destiny_manifest';

    protected $casts = [
        'json' => 'json',
    ];

    protected $fillable = [
        '*',
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
