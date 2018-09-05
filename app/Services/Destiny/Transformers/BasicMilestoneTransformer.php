<?php declare(strict_types=1);

namespace App\Services\Destiny\Transformers;

use App\Milestone;

class BasicMilestoneTransformer extends AbstractTransformer
{
    public function __invoke(Milestone $milestone): array
    {
        $thumbUrl = data_get($milestone, 'json.displayProperties.icon', '');

        return [
            'title'     => data_get($milestone, 'json.displayProperties.name', ''),
            'text'      => data_get($milestone, 'json.displayProperties.description', ''),
            'thumb_url' => empty($thumbUrl) ? $thumbUrl : $this->getInvertedIcon($thumbUrl),
            'color'     => '#121C4D',
        ];
    }
}
