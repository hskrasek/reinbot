<?php

namespace App\Console\Commands;

use App\Milestone;
use App\MilestoneRepository;
use App\Services\Destiny\Client;
use App\Services\Destiny\MilestoneTransformer;
use Illuminate\Console\Command;

class PostMilestones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'destiny:milestones {--debug : Dump the attachments instead of sending to Slack}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Post the weeks milestones';

    /**
     * @var \App\Services\Destiny\Client
     */
    private $client;

    /**
     * @var MilestoneRepository
     */
    private $milestones;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Client $client, MilestoneRepository $milestones)
    {
        parent::__construct();
        $this->client     = $client;
        $this->milestones = $milestones;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $milestones = $this->client->getMilestones();

        $attachments = $milestones->map(function (array $milestone) {
            return $this->milestones->getMilestoneFromManifest($milestone);
        })->except(4253138191)->map(function (Milestone $milestone) {
            return MilestoneTransformer::transform($milestone);
        });

        if ($this->option('debug')) {
            $this->line('Dumping attachments');
            dd($attachments->toArray());
        }

        (new \GuzzleHttp\Client())->post(
            config('services.destiny.slack_web_hook'),
            [
                'json' => [
                    'text'        => 'Incoming transmission!',
                    'attachments' => $attachments->filter()->toArray(),
                ],
            ]
        );
    }

    private function getQuestInformation(array $milestone)
    {
        if (!array_has($milestone, 'availableQuests')) {
            return $milestone;
        }

        foreach ($milestone['availableQuests'] as $key => $availableQuest) {
            $milestone['availableQuests'][$key] = array_merge(
                $availableQuest,
                $this->client->getItemDefinition($availableQuest['questItemHash'])
            );

            if (array_has($availableQuest, 'activity')) {
                $milestone['availableQuests'][$key]['activity'] = array_merge(
                    $availableQuest['activity'],
                    $this->client->getActivityDefinition($availableQuest['activity']['activityHash'])
                );

                if (array_has($availableQuest, 'activity.modifierHashes')) {
                    foreach ($milestone['availableQuests'][$key]['activity']['modifierHashes'] as $modifierKey => $modifierHash) {
                        $milestone['availableQuests'][$key]['activity']['activity_modifiers'][] = $this->client->getModifierDefinition($modifierHash);
                    }
                }
            }

            if (array_has($availableQuest, 'challenges')) {
                foreach ($availableQuest['challenges'] as $challengeKey => $challenge) {
                    $milestone['availableQuests'][$key]['challenges'][$challengeKey] = array_merge(
                        $challenge,
                        $this->client->getObjectiveDefinition($challenge['objectiveHash'])
                    );
                }
            }
        }

        return $milestone;
    }
}
