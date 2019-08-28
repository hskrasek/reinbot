<?php declare(strict_types=1);

namespace App\Slack\Builder;

use App\Milestone;
use App\Quest;
use App\Slack\Accessory;
use App\Slack\Block;

class FlashpointBuilder extends AbstractBuilder
{
    public function buildBlocks(int $milestoneHash, array $milestoneData): array
    {
        $blocks = [];
        $milestone = Milestone::byBungieId($milestoneHash)->first();
        $quest = Quest::byBungieId(array_get($milestoneData, 'availableQuests.0.questItemHash'))->first();

        $blocks[] = Block::sectionWithImage(
            '*' . data_get(
                $quest->json,
                'displayProperties.name'
            ) . "*\n\n" . data_get($milestone->json, 'displayProperties.description'),
            Accessory::make(
                'https://bungie.net' . data_get($milestone->json, 'displayProperties.icon'),
                data_get($milestone->json, 'displayProperties.description')
            )
        );

        $blocks[] = Block::divider();

        return $blocks;
    }
}
