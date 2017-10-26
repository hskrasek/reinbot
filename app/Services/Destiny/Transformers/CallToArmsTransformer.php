<?php namespace App\Services\Destiny\Transformers;

class CallToArmsTransformer extends AbstractTransformer
{
    public function __invoke(array $milestone): array
    {
        $thumbUrl = array_get($milestone, 'availableQuests.0.displayProperties.icon', '');
        $imageUrl = array_get($milestone, 'availableQuests.0.pgcr_image', '');

        return [
            'title'     => array_get($milestone, 'availableQuests.0.displayProperties.name', ''),
            'text'      => array_get($milestone, 'about', ''),
            'thumb_url' => empty($thumbUrl) ? $thumbUrl : $this->getInvertedIcon('https://www.bungie.net' . $thumbUrl),
            'image_url' => empty($imageUrl) ? $imageUrl : 'https://www.bungie.net' . $imageUrl,
            'color'     => '#6B0B10',
        ];
    }
}
