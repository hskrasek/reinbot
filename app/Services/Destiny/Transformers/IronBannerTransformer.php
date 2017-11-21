<?php namespace App\Services\Destiny\Transformers;

class IronBannerTransformer extends AbstractTransformer
{
    public function __invoke(array $milestone): array
    {
        $thumbUrl = array_get($milestone, 'availableQuests.0.displayProperties.icon', '');

        return [
            'title'     => array_get($milestone, 'availableQuests.0.activity.displayProperties.name', ''),
            'text'      => array_get($milestone, 'availableQuests.0.activity.displayProperties.description', ''),
            'thumb_url' => empty($thumbUrl) ? $thumbUrl : $this->getInvertedIcon($thumbUrl),
            'color'     => '#160A09',
        ];
    }
}
