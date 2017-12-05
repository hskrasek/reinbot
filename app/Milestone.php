<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    protected $table = 'DestinyMilestoneDefinition';

    protected $connection = 'destiny_manifest';

    protected $casts = [
        'json' => 'json'
    ];
}
