<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Stat
 *
 * @property int $id
 * @property array $json
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Stat byBungieId($id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Stat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Stat whereJson($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Stat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Stat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Stat query()
 */
class Stat extends Model
{
    protected $table = 'DestinyStatDefinition';

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
