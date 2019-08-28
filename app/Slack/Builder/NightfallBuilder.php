<?php declare(strict_types=1);

namespace App\Slack\Builder;

use App\Activity;
use App\Milestone;
use App\Slack\Accessory;
use App\Slack\Block;
use Illuminate\Support\Collection;

class NightfallBuilder extends AbstractBuilder
{
    public function buildBlocks(int $milestoneHash, array $milestoneData): array
    {
        $blocks = [];
        $milestone = Milestone::byBungieId($milestoneHash)->first();

        $blocks[] = Block::sectionWithImage(
            '*' . data_get(
                $milestone->json,
                'displayProperties.name'
            ) . "*\n\n" . data_get($milestone->json, 'displayProperties.description'),
            Accessory::make(
                'https://bungie.net' . data_get($milestone->json, 'displayProperties.icon'),
                data_get($milestone->json, 'displayProperties.description')
            )
        );

        Collection::make($milestoneData['activities'])
            ->filter(function (array $activityData) {
                return array_key_exists('modifierHashes', $activityData);
            })
            ->map(function (array $activityData) {
                $activity = Activity::byBungieId($activityData['activityHash'])->first();

                return Block::section(
                    '*' . data_get(
                        $activity->json,
                        'selectionScreenDisplayProperties.name'
                    ) . "*\n" . data_get($activity->json, 'displayProperties.description')
                );
            })
            ->each(function (Block $block) use (&$blocks) {
                $blocks[] = $block;
            });

        $blocks[] = Block::divider();

        return $blocks;
    }
}
