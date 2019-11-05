<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Activity
 *
 * @property int $id
 * @property array $json
 * @property \Illuminate\Support\Collection|Modifier[] $modifiers
 * @property \Illuminate\Support\Collection|InventoryItem[] $rewards
 * @property Destination $destination
 * @property Place $place
 * @property \Illuminate\Support\Collection|Challenge[] $challenges
 * @property ActivityMode $mode
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity byBungieId($id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity whereJson($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity whereBungieIdIn($ids)
 */
class Activity extends Model
{
    protected $table = 'DestinyActivityDefinition';

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
