<?php namespace App\Services\Destiny;

use App\Milestone;
use App\Services\Destiny\Transformers\CallToArmsTransformer;
use App\Services\Destiny\Transformers\ClanXPTransformer;
use App\Services\Destiny\Transformers\CrimsonDaysTransformer;
use App\Services\Destiny\Transformers\FactionRallyTransformer;
use App\Services\Destiny\Transformers\FlashpointTransformer;
use App\Services\Destiny\Transformers\HeroicStrikesTransformer;
use App\Services\Destiny\Transformers\IronBannerTransformer;
use App\Services\Destiny\Transformers\LeviathanTransformer;
use App\Services\Destiny\Transformers\MeditationsTransformer;
use App\Services\Destiny\Transformers\NightfallTransformer;

class MilestoneTransformer
{
    /**
     * @param Milestone $milestone
     *
     * @return array
     */
    public static function transform(Milestone $milestone)
    {
        //TODO: Match descriptions within the companion app if possible
        switch (sprintf('%u', $milestone->id & 0xFFFFFFFF)) {
            case 2171429505:
                return (new NightfallTransformer)($milestone);
            case 202035466:
                return (new CallToArmsTransformer)($milestone);
            case 463010297:
                return (new FlashpointTransformer)($milestone);
            case 3660836525:
                return (new LeviathanTransformer)($milestone);
            case 3245985898:
                // TODO: Find a way to include all the meditations quests
                return (new MeditationsTransformer)($milestone);
            case 3603098564:
                return (new ClanXPTransformer)($milestone);
            case 1718587363:
                return (new FactionRallyTransformer)($milestone);
            case 3205009061:
                return (new FactionRallyTransformer)($milestone);
            case 4248276869:
                return (new IronBannerTransformer)($milestone);
            case 3405519164:
                return (new HeroicStrikesTransformer)($milestone);
            case 120184767:
                return (new CrimsonDaysTransformer)($milestone);
        }

        return [];
    }
}
