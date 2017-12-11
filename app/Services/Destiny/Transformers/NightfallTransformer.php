<?php namespace App\Services\Destiny\Transformers;

use App\Activity;
use App\Milestone;
use App\Modifier;
use App\Quest;

class NightfallTransformer extends AbstractTransformer
{
    public function __invoke(Milestone $milestone): array
    {
        /** @var Quest $quest */
        $quest = $milestone->quests->first();
        /** @var Activity $activity */
        $activity = $quest->activity;

        $thumbUrl = data_get($quest, 'json.displayProperties.icon', '');
        $imageUrl = data_get($activity, 'json.pgcrImage', '');

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
            'image_url' => empty($imageUrl) ? $imageUrl : 'https://www.bungie.net' . $imageUrl,
            'fields'    => $this->buildModifierArray($activity),
            'color'     => '#526283',
        ];
    }

    private function buildModifierArray(Activity $activity)
    {
        return $activity->modifiers->map(function (Modifier $modifier) {
            return [
                'title' => data_get($modifier, 'json.displayProperties.name', ''),
                'value' => data_get($modifier, 'json.displayProperties.description', ''),
                'short' => true,
            ];
        })->toArray();
    }
}
