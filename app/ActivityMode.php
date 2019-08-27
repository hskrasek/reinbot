<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ActivityMode
 *
 * @property int $id
 * @property array $json
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActivityMode byBungieId($id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActivityMode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActivityMode whereJson($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActivityMode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActivityMode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActivityMode query()
 */
class ActivityMode extends Model
{
    protected $table = 'DestinyActivityModeDefinition';

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
