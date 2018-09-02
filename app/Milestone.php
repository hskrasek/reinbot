<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Milestone
 *
 * @property int $id
 * @property array $json
 * @property \Illuminate\Support\Collection|Quest[] $quests
 * @property \Illuminate\Support\Collection|Activity[] $activities
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Milestone byBungieId($id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Milestone whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Milestone whereJson($value)
 * @mixin \Eloquent
 */
class Milestone extends Model
{
    protected $table = 'DestinyMilestoneDefinition';

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
