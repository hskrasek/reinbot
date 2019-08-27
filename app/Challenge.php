<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Challenge
 *
 * @property int $id
 * @property array $json
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Challenge byBungieId($id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Challenge whereBungieIdIn($ids)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Challenge whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Challenge whereJson($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Challenge newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Challenge newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Challenge query()
 */
class Challenge extends Model
{
    protected $table = 'DestinyObjectiveDefinition';

    protected $connection = 'destiny_manifest';

    protected $casts = [
        'json' => 'json',
    ];

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $id
     *
     * @return \Illuminate\Database\Eloquent\Builder
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
