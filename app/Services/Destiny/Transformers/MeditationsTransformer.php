<?php namespace App\Services\Destiny\Transformers;

class MeditationsTransformer
{
    public function __invoke(array $milestone): array
    {
        $thumbUrl = array_get($milestone, 'availableQuests.0.displayProperties.icon', '');

        return [
            'title'     => array_get($milestone, 'availableQuests.0.displayProperties.name', ''),
            'text'      => array_get($milestone, 'about', ''),
            'thumb_url' => empty($thumbUrl) ? $thumbUrl : 'https://www.bungie.net' . $thumbUrl,
            'color'     => '#F7324B',
        ];
    }
}
