<?php declare(strict_types=1);

namespace App\Services\Destiny\Transformers;

use App\Activity;
use App\Milestone;
use App\Quest;

class HeroicStrikesTransformer extends AbstractTransformer
{
    public function __invoke(Milestone $milestone): array
    {
        /** @var Quest $quest */
        $quest = $milestone->quests->first();
        /** @var Activity $activity */
        $activity = $quest->activity;
        $thumbUrl = data_get($quest, 'json.displayProperties.icon', '');

        return [
            'title'      => data_get($quest, 'json.displayProperties.name', ''),
            'title_link' => config('app.url') . '/destiny2/activities/' . data_get($activity, 'json.hash'),
            'text'       => array_get($milestone, 'json.displayProperties.description', ''),
            'thumb_url'  => empty($thumbUrl) ? $thumbUrl : $this->getInvertedIcon($thumbUrl),
            'color'      => '#526283',
        ];
    }
}
