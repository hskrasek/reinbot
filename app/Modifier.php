<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Modifier
 *
 * @property int $id
 * @property array $json
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modifier byBungieId($id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modifier whereBungieIdIn($ids)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modifier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modifier whereJson($value)
 * @mixin \Eloquent
 */
class Modifier extends Model
{
    protected $table = 'DestinyActivityModifierDefinition';

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
