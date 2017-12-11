<?php namespace App;

use Illuminate\Support\Collection;

class ChallengeRepository
{
    /**
     * @param Activity|null $activity
     * @param array $apiChallenges
     *
     * @return Collection
     */
    public function getChallengesForQuest(?Activity $activity, array $apiChallenges): Collection
    {
        if (null === $activity) {
            return Collection::make();
        }

        return Challenge::whereBungieIdIn(array_pluck(array_where($apiChallenges, function ($value) use ($activity) {
            return $value['activityHash'] === $activity->id || ($value['activityHash'] - 4294967296) === $activity->id;
        }), 'objectiveHash'))->get();
    }
}
