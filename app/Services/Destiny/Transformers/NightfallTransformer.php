<?php namespace App\Services\Destiny\Transformers;

use App\Activity;
use App\Milestone;
use App\Modifier;
use App\Quest;

class NightfallTransformer extends AbstractTransformer
{
    public function __invoke(Milestone $milestone): array
    {
        $thumbUrl = data_get($milestone, 'json.displayProperties.icon', '');
        $imageUrl = data_get($milestone, 'json.image', '');

        return [
            'title'     => data_get(
                $milestone,
                'json.displayProperties.name',
                data_get($milestone, 'json.displayProperties.name')
            ),
            // 'title_link' => config('app.url') . '/destiny2/activities/' . data_get($activity, 'json.hash'),
            'text'      => data_get(
                $milestone,
                'json.displayProperties.description',
                data_get($milestone, 'json.displayProperties.description')
            ),
            'thumb_url' => empty($thumbUrl) ? $thumbUrl : $this->getInvertedIcon($thumbUrl),
            'image_url' => empty($imageUrl) ? $imageUrl : 'https://www.bungie.net' . $imageUrl,
            // 'fields'    => $this->buildModifierArray($activity),
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
