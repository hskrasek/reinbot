<?php namespace App\Services\Destiny\Transformers;

class NightfallTransformer extends AbstractTransformer
{
    public function __invoke(array $milestone): array
    {
        $thumbUrl = array_get($milestone, 'availableQuests.0.displayProperties.icon', '');
        $imageUrl = array_get($milestone, 'availableQuests.0.activity.pgcrImage', '');

        return [
            'title'     => array_get($milestone, 'availableQuests.0.activity.displayProperties.name', ''),
            'text'      => array_get($milestone, 'availableQuests.0.activity.displayProperties.description', ''),
            'thumb_url' => empty($thumbUrl) ? $thumbUrl : $this->getInvertedIcon($thumbUrl),
            'image_url' => empty($imageUrl) ? $imageUrl : 'https://www.bungie.net' . $imageUrl,
            'fields'    => $this->buildModifierArray($milestone),
            'color'     => '#526283',
        ];
    }

    private function buildModifierArray($milestone)
    {
        return collect(array_get($milestone, 'availableQuests.0.activity.activity_modifiers'))->map(function (
            array $modifier
        ) {
            return [
                'title' => array_get($modifier, 'displayProperties.name', ''),
                'value' => array_get($modifier, 'displayProperties.description', ''),
                'short' => true,
            ];
        })->toArray();
    }
}
