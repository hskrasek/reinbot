<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\CharacterClass
 *
 * @property int $id
 * @property array $json
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CharacterClass whereClass($classType)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CharacterClass whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CharacterClass whereJson($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CharacterClass newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CharacterClass newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CharacterClass query()
 */
class CharacterClass extends Model
{
    protected $table = 'DestinyClassDefinition';

    protected $connection = 'destiny_manifest';

    protected $casts = [
        'json' => 'json',
    ];

    public function scopeWhereClass(Builder $builder, int $classType)
    {
        return $builder->whereRaw("json_extract(json, '$.classType') = ?", [$classType]);
    }
}
