<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ClassType extends Model
{
    protected $table = 'DestinyClassDefinition';

    protected $connection = 'destiny_manifest';

    protected $casts = [
        'json' => 'json',
    ];

    public function scopeWhereClassType(Builder $builder, int $classType)
    {
        return $builder->whereRaw("json_extract(json, '$.classType') = ?", [$classType]);
    }
}
