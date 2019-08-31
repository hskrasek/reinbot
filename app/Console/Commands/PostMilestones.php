<?php

namespace App\Console\Commands;

use App\Services\Destiny\Client;
use App\Slack\BlockBuilder;
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
     * @var BlockBuilder
     */
    private $blockBuilder;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Client $client, BlockBuilder $blockBuilder)
    {
        parent::__construct();
        $this->client = $client;
        $this->blockBuilder = $blockBuilder;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $milestones = $this->client->getMilestones();

        $blocks = $this->blockBuilder->buildForMilestones($milestones->toArray());

        // foreach ($salesItems as $index => $item) {
        //     $itemsForSale[] = InventoryItem::where('id', $item['itemHash'])->orWhere(
        //         'id',
        //         $item['itemHash'] - 4294967296
        //     )->first();
        // }

        if ($this->option('debug')) {
            $this->line('Dumping blocks');
            dd(collect($blocks)->toArray());
        }

        $response = (new \GuzzleHttp\Client())->post('https://slack.com/api/chat.postMessage', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('SLACK_API_TOKEN'),
            ],
            'json'    => [
                'channel' => 'G622FQ124',
                'text'    => 'Incoming transmission!',
                'blocks'  => $blocks,
            ],
        ]);
    }
}
