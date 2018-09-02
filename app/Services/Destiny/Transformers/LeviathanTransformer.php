<?php declare(strict_types=1);

namespace App\Services\Destiny\Transformers;

use App\Activity;
use App\Challenge;
use App\Milestone;
use App\Quest;

class LeviathanTransformer extends AbstractTransformer
{
    private const ROTATIONS = [
        2693136605 => 'Gauntlet, Pleasure Gardens, Royal Pools',
        2693136604 => 'Gauntlet, Royal Pools, Pleasure Gardens',
        2693136602 => 'Pleasure Gardens, Gauntlet, Royal Pools',
        2693136603 => 'Pleasure Gardens, Royal Pools, Gauntlet',
        2693136600 => 'Royal Pools, Gauntlet, Pleasure Gardens',
        2693136601 => 'Royal Pools, Pleasure Gardens, Gauntlet',
    ];

    public function __invoke(Milestone $milestone): array
    {
        $activity = $milestone->activities->first();
        $thumbUrl = data_get($milestone, 'json.displayProperties.icon', '');

        return [
            'title'      => data_get($milestone, 'json.displayProperties.name', ''),
            'title_link' => config('app.url') . '/destiny2/activities/' . data_get($activity, 'json.hash'),
            'text'       => data_get(
                $milestone,
                'json.displayProperties.description',
                ''
            ),
            'thumb_url'  => 'https://www.bungie.net' . $thumbUrl,
            // 'fields'     => $this->buildChallengesArray($activity),
            'color'      => '#1C0B3C',
        ];
    }

    private function buildChallengesArray(Activity $activity): array
    {
        return $this->getRaidRotation($activity);
    }

    private function getRaidRotation(Activity $activity): array
    {
        return [
            'title' => 'Rotation',
            'value' => self::ROTATIONS[data_get($activity, 'json.hash')],
            'short' => true,
        ];
    }
}
