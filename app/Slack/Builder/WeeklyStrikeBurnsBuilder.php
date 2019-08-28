<?php declare(strict_types=1);

namespace App\Slack\Builder;

use App\Milestone;
use App\Modifier;
use App\Slack\Accessory;
use App\Slack\Block;
use Illuminate\Support\Collection;

class WeeklyStrikeBurnsBuilder extends AbstractBuilder
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

        Collection::make($milestoneData['activities'][0]['modifierHashes'])
            ->map(function (int $modifierHash) {
                $modifier = Modifier::byBungieId($modifierHash)->first();

                return Block::sectionWithImage(
                    '*' . data_get(
                        $modifier->json,
                        'displayProperties.name'
                    ) . "*\n" . data_get($modifier->json, 'displayProperties.description'),
                    Accessory::make(
                        'https://bungie.net' . data_get($modifier->json, 'displayProperties.icon'),
                        data_get($modifier->json, 'displayProperties.description')
                    )
                );
            })
            ->each(function (Block $block) use (&$blocks) {
                $blocks[] = $block;
            });

        $blocks[] = Block::divider();

        return $blocks;
    }
}
