<?php namespace App\Services\Destiny\Transformers;

class LeviathanTransformer extends AbstractTransformer
{
    public function __invoke(array $milestone): array
    {
        $thumbUrl = array_get($milestone, 'availableQuests.0.displayProperties.icon', '');
        $imageUrl = array_get($milestone, 'availableQuests.0.pgcr_image', '');

        return [
            'title'     => array_get($milestone, 'availableQuests.0.activity.displayProperties.name', ''),
            'text'      => array_get($milestone, 'about', ''),
            'thumb_url' => empty($thumbUrl) ? $thumbUrl : $this->getInvertedIcon('https://www.bungie.net' . $thumbUrl),
            'image_url' => empty($imageUrl) ? $imageUrl : 'https://www.bungie.net' . $imageUrl,
            'fields'    => $this->buildChallengesArray($milestone),
            'color'     => '#1C0B3C',
        ];
    }

    private function buildChallengesArray($milestone): array
    {
        return collect(array_get($milestone, 'availableQuests.0.challenges'))->filter(function ($challenge) use (
            $milestone
        ) {
            return array_get($challenge, 'activityHash') === array_get($milestone,
                    'availableQuests.0.activity.activityHash');
        })->map(function (array $challenge) {
            return [
                'title' => array_get($challenge, 'displayProperties.name', ''),
                'value' => array_get($challenge, 'displayProperties.description', ''),
                'short' => true,
            ];
        })->toArray();
    }
}
