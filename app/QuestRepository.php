<?php declare(strict_types=1);

namespace App;

use App\Repositories\ActivityRepository;
use Illuminate\Support\Collection;

class QuestRepository
{
    /**
     * @var ChallengeRepository
     */
    private $challenges;

    /**
     * @var ActivityRepository
     */
    private $activities;

    public function __construct(ChallengeRepository $challenges, ActivityRepository $activities)
    {
        $this->challenges = $challenges;
        $this->activities = $activities;
    }

    /**
     * @param array $availableQuests
     *
     * @return Collection
     */
    public function getQuestsForMilestone(array $availableQuests): Collection
    {
        return Quest::whereBungieIdIn(array_pluck($availableQuests, 'questItemHash'))->get()->map(function (
            Quest $quest,
            $key
        ) use ($availableQuests) {
            $quest->activity = $activity = $this->activities->getActivityForQuest(array_get(
                $availableQuests,
                "$key.activity",
                []
            ));

            $quest->challenges = $this->challenges->getChallengesForQuest(
                $activity,
                array_get($availableQuests, "$key.challenges", [])
            );

            return $quest;
        });
    }
}
