<?php declare(strict_types=1);

namespace App\Services\Destiny\Transformers;

use App\Challenge;
use App\Milestone;
use App\Quest;

class LeviathanTransformer extends AbstractTransformer
{
    public function __invoke(Milestone $milestone): array
    {
        /** @var Quest $quest */
        $quest    = $milestone->quests->first();
        $thumbUrl = data_get($quest, 'json.displayProperties.icon', '');

        return [
            'title'     => data_get($quest, 'json.displayProperties.name', ''),
            'text'      => data_get(
                $milestone,
                'json.quests.' . sprintf('%u', $quest->id & 0xFFFFFFFF) . '.displayProperties.description',
                ''
            ),
            'thumb_url' => empty($thumbUrl) ? $thumbUrl : $this->getInvertedIcon($thumbUrl),
            'fields'    => $this->buildChallengesArray($quest),
            'color'     => '#1C0B3C',
        ];
    }

    private function buildChallengesArray(Quest $quest): array
    {
        return $quest->challenges->map(function (Challenge $challenge) {
            return [
                'title' => data_get($challenge, 'json.displayProperties.name', ''),
                'value' => data_get($challenge, 'json.displayProperties.description', ''),
                'short' => true,
            ];
        })->toArray();
    }
}
