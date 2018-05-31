<?php namespace App\Repositories;

use App\Activity;
use App\ActivityMode;
use App\ChallengeRepository;
use App\Destination;
use App\InventoryItem;
use App\Modifier;
use App\Place;
use Illuminate\Support\Collection;

class ActivityRepository
{
    /**
     * @var ChallengeRepository
     */
    private $challenges;

    public function __construct(ChallengeRepository $challenges)
    {
        $this->challenges = $challenges;
    }

    public function getActivityByHash(int $hash): ?Activity
    {
        return tap(Activity::byBungieId($hash)->first(), function (Activity $activity) {
            $activity->rewards = Collection::make(data_get($activity, 'json.rewards.*.rewardItems.*'))
                ->pluck('itemHash')
                ->map(function (int $itemHash) {
                    return InventoryItem::byBungieId($itemHash)->first();
                });
            $activity->destination = Destination::byBungieId(data_get($activity, 'json.destinationHash'))->first();
            $activity->place = Place::byBungieId(data_get($activity, 'json.placeHash'))->first();
            $activity->challenges = $this->challenges->getChallengesForActivity($activity);
            $activity->mode = ActivityMode::byBungieId(data_get($activity, 'json.directActivityModeHash'))->first();
        });
    }

    public function getActivityForQuest(array $activity): ?Activity
    {
        return tap(
            Activity::byBungieId(array_get($activity, 'activityHash'))->first(),
            function (?Activity $manifestActivity) use ($activity): ?Activity {
                if (null === $manifestActivity) {
                    return null;
                }

                $manifestActivity->modifiers = Modifier::whereBungieIdIn(array_get(
                    $activity,
                    'modifierHashes',
                    []
                ))->get();

                return $manifestActivity;
            }
        );
    }
}
