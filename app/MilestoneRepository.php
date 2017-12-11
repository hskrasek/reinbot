<?php namespace App;

class MilestoneRepository
{
    /**
     * @var QuestRepository
     */
    private $quests;

    public function __construct(QuestRepository $quests)
    {
        $this->quests = $quests;
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

                return $milestone;
            }
        );
    }
}
