<?php declare(strict_types=1);

namespace App\Services\Destiny\Transformers;

use App\Milestone;

class FactionRallyTransformer extends AbstractTransformer
{
    public function __invoke(Milestone $milestone): array
    {
        $quest    = $milestone->quests->first();
        $thumbUrl = data_get($quest, 'json.displayProperties.icon', '');

        return [
            'title'     => data_get($quest, 'json.displayProperties.name', ''),
            'text'      => 'Arach Jalaal from Dead Orbit, Lakshmi-2 from Future War Cult, and Executor Hideo of New Monarchy have returned to the Tower to ask Guardians to pledge their loyalty. During a Faction Rally, players may pledge to one of these Factions to gather supplies and destroy enemy resources.',
            'thumb_url' => empty($thumbUrl) ? $thumbUrl : $this->getInvertedIcon($thumbUrl),
            'color'     => '#DE58E6',
        ];
    }
}
