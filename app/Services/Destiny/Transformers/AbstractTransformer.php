<?php namespace App\Services\Destiny\Transformers;

use App\Milestone;
use Image;

abstract class AbstractTransformer
{
    /**
     * Creates, saves, and returns an inverted icon to use in Slack.
     *
     * @param string $thumbUrl
     *
     * @return string
     */
    protected function getInvertedIcon(string $thumbUrl): string
    {
        $thumbUrl = 'https://www.bungie.net' . $thumbUrl;
        Image::cache(function ($image) use ($thumbUrl) {
            $image->make($thumbUrl)->invert()->save(storage_path('app/public/' . basename($thumbUrl)));
        }, 10080, true);

        return asset('storage/' . basename($thumbUrl));
    }

    protected function getMilestoneTimeframe(Milestone $milestone): array
    {
        return [
            'title' => 'Time Frame',
            'value' => $milestone->startDate->format('D, M d \@ H:i') . ' to '. $milestone->endDate->format('D, M d \@ H:i'),
            'short' => true,
        ];
    }
}
