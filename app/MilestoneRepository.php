<?php namespace App;

use App\Repositories\ActivityRepository;

class MilestoneRepository
{
    /**
     * @var QuestRepository
     */
    private $quests;

    /**
     * @var ActivityRepository
     */
    private $activities;

    public function __construct(QuestRepository $quests, ActivityRepository $activities)
    {
        $this->quests     = $quests;
        $this->activities = $activities;
    }

    public function getMilestoneFromManifest(array $apiManifest): Milestone
    {
        return tap(
            Milestone::byBungieId($apiManifest['milestoneHash'])->first(),
            function (Milestone $milestone) use ($apiManifest) {
                $milestone->quests = $this->quests->getQuestsForMilestone(array_get(
                    $apiManifest,
                    'availableQuests',
                    []
                ));

                if (array_has($apiManifest, 'activities')) {
                    $milestone->activities = $this->activities->getActivities(
                        array_pluck(array_get($apiManifest, 'activities', []), 'activityHash')
                    );
                }

                return $milestone;
            }
        );
    }
}
