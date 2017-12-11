<?php declare(strict_types=1);

namespace App\Services\Destiny\Transformers;

use App\Activity;
use App\Milestone;
use App\Quest;

class IronBannerTransformer extends AbstractTransformer
{
    public function __invoke(Milestone $milestone): array
    {
        /** @var Quest $quest */
        $quest = $milestone->quests->first();
        /** @var Activity $activity */
        $activity = $quest->activity;

        $thumbUrl = data_get($quest, 'json.displayProperties.icon', '');

        return [
            'title'     => data_get(
                $activity,
                'json.displayProperties.name',
                data_get($milestone, 'json.displayProperties.name')
            ),
            'text'      => data_get(
                $activity,
                'json.displayProperties.description',
                data_get($milestone, 'json.displayProperties.description')
            ),
            'thumb_url' => empty($thumbUrl) ? $thumbUrl : $this->getInvertedIcon($thumbUrl),
            'color'     => '#160A09',
        ];
    }
}
