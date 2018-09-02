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
        $this->client = $client;
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
        })->map(function (Milestone $milestone) {
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
}
