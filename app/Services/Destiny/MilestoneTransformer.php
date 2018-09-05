<?php namespace App\Services\Destiny;

use App\Milestone;
use App\Services\Destiny\Transformers\BasicMilestoneTransformer;
use App\Services\Destiny\Transformers\CallToArmsTransformer;
use App\Services\Destiny\Transformers\ClanXPTransformer;
use App\Services\Destiny\Transformers\CrimsonDaysTransformer;
use App\Services\Destiny\Transformers\FactionRallyTransformer;
use App\Services\Destiny\Transformers\FlashpointTransformer;
use App\Services\Destiny\Transformers\GuardianForAllTransformer;
use App\Services\Destiny\Transformers\HeroicStrikesTransformer;
use App\Services\Destiny\Transformers\IronBannerTransformer;
use App\Services\Destiny\Transformers\MeditationsTransformer;
use App\Services\Destiny\Transformers\NightfallTransformer;
use App\Services\Destiny\Transformers\RaidTransformer;

class MilestoneTransformer
{
    /**
     * @param Milestone $milestone
     *
     * @return array
     */
    public static function transform(Milestone $milestone): array
    {
        //TODO: Match descriptions within the companion app if possible
        switch (sprintf('%u', $milestone->id & 0xFFFFFFFF)) {
            case 2171429505:
                return app()->call(NightfallTransformer::class, ['milestone' => $milestone], '__invoke');
            case 202035466:
                return app()->call(CallToArmsTransformer::class, ['milestone' => $milestone], '__invoke');
            case 463010297:
                return app()->call(FlashpointTransformer::class, ['milestone' => $milestone], '__invoke');
            case 3660836525:
            case 2986584050:
            case 2683538554:
                return app()->call(RaidTransformer::class, ['milestone' => $milestone], '__invoke');
            case 3245985898:
                // TODO: Find a way to include all the meditations quests
                return app()->call(MeditationsTransformer::class, ['milestone' => $milestone], '__invoke');
            case 3603098564:
            case 4253138191:
                return app()->call(ClanXPTransformer::class, ['milestone' => $milestone], '__invoke');
            case 1718587363:
                return app()->call(FactionRallyTransformer::class, ['milestone' => $milestone], '__invoke');
            case 3205009061:
                return app()->call(FactionRallyTransformer::class, ['milestone' => $milestone], '__invoke');
            case 4248276869:
                return app()->call(IronBannerTransformer::class, ['milestone' => $milestone], '__invoke');
            case 3405519164:
                return app()->call(HeroicStrikesTransformer::class, ['milestone' => $milestone], '__invoke');
            case 120184767:
                return app()->call(CrimsonDaysTransformer::class, ['milestone' => $milestone], '__invoke');
            case 3082135827:
            case 157823523:
            case 1437935813:
            case 3448738070:
                return app()->call(BasicMilestoneTransformer::class, ['milestone' => $milestone], '__invoke');
            case 536115997:
                return app()->call(GuardianForAllTransformer::class, ['milestone' => $milestone], '__invoke');
            // case 534869653:
            //     //XUR
        }

        return [];
    }
}
