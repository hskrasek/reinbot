<?php declare(strict_types=1);

namespace App\Services\Destiny\Transformers;

use App\Milestone;

class GuardianForAllTransformer extends AbstractTransformer
{
    public function __invoke(Milestone $milestone): array
    {
        /** @var Quest $quest */
        $quest = $milestone->quests->first();
        $thumbUrl = data_get($quest, 'json.displayProperties.icon', '');

        return [
            'title'     => data_get($quest, 'json.displayProperties.name', ''),
            'text'      => data_get(
                $milestone,
                'json.quests.' . sprintf('%u', $quest->id & 0xFFFFFFFF) . '.displayProperties.description',
                ''
            ),
            'thumb_url' => empty($thumbUrl) ? $thumbUrl : $this->getInvertedIcon($thumbUrl),
        ];
    }
}
