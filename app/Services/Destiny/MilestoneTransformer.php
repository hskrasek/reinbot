<?php namespace App\Services\Destiny;

use App\Services\Destiny\Transformers\CallToArmsTransformer;
use App\Services\Destiny\Transformers\FlashpointTransformer;
use App\Services\Destiny\Transformers\LeviathanTransformer;
use App\Services\Destiny\Transformers\MeditationsTransformer;
use App\Services\Destiny\Transformers\NightfallTransformer;

class MilestoneTransformer
{
    /**
     * @param array $milestone
     *
     * @return array
     */
    public static function transform(array $milestone)
    {
        switch ($milestone['milestoneHash']) {
            case 2171429505: {
                return (new NightfallTransformer)($milestone);
            }
            case 202035466: {
                return (new CallToArmsTransformer)($milestone);
            }
            case 463010297: {
                return (new FlashpointTransformer)($milestone);
            }
            case 3660836525: {
                return (new LeviathanTransformer)($milestone);
            }
            case 3245985898: {
                // TODO: Find a way to include all the meditations quests
                return (new MeditationsTransformer)($milestone);
            }
            case 3603098564: {
                return [];
            }
        }

        return [];
    }
}
