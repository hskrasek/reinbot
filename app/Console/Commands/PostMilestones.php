<?php

namespace App\Console\Commands;

use App\Services\Destiny\Client;
use Illuminate\Console\Command;

class PostMilestones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'destiny:milestones';

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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $milestones = $this->client->getMilestones();

        // dd($milestones->map(function (array $milestone) {
        //     return array_merge($milestone, $this->client->getMilestoneContent($milestone['milestoneHash']));
        // })->map(function (array $milestone) {
        //     return $this->getQuestInformation($milestone);
        // })->filter(function (array $milestone) {
        //     return array_has($milestone, 'about');
        // })->except(4253138191)->map(function (array $milestone) {
        //     return [
        //         'title' => array_get($milestone, 'availableQuests.0.displayProperties.name', ''),
        //         'text'  => array_get($milestone, 'about', ''),
        //     ];
        // }));

        (new \GuzzleHttp\Client())->post(config('services.destiny.slack_web_hook'),
            [
                'json' => [
                    'text'        => 'Incoming transmission!',
                    'attachments' => $milestones->map(function (array $milestone) {
                        return array_merge($milestone, $this->client->getMilestoneContent($milestone['milestoneHash']));
                    })->map(function (array $milestone) {
                        return $this->getQuestInformation($milestone);
                    })->filter(function (array $milestone) {
                        return array_has($milestone, 'about');
                    })->except(4253138191)->map(function (array $milestone) {
                        $thumbUrl = array_get($milestone, 'availableQuests.0.displayProperties.icon', '');
                        $imageUrl = array_get($milestone, 'availableQuests.0.pgcr_image', '');
                        return [
                            'title' => array_get($milestone, 'availableQuests.0.displayProperties.name', ''),
                            'text'  => array_get($milestone, 'about', ''),
                            'thumb_url' => empty($thumbUrl) ? $thumbUrl : 'https://www.bungie.net' . $thumbUrl,
                            'image_url' => empty($imageUrl) ? $imageUrl : 'https://www.bungie.net' . $imageUrl,
                        ];
                    })->toArray(),
                ],
            ]);
    }

    private function getQuestInformation(array $milestone)
    {
        if (!array_has($milestone, 'availableQuests')) {
            return $milestone;
        }

        foreach ($milestone['availableQuests'] as $key => $availableQuest) {
            $milestone['availableQuests'][$key] = array_merge($availableQuest,
                $this->client->getItemDefinition($availableQuest['questItemHash']));

            if (array_has($availableQuest, 'activity')) {
                $milestone['availableQuests'][$key]['activity'] = array_merge($availableQuest['activity'],
                    $this->client->getActivityDefinition($availableQuest['activity']['activityHash']));
            }
        }

        return $milestone;
    }
}
