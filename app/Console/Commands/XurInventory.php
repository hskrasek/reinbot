<?php

namespace App\Console\Commands;

use App\Services\Destiny\Xur;
use Illuminate\Console\Command;

class XurInventory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'destiny:xur {--debug : Dump the attachments instead of sending to Slack}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Post Xur\'s inventory';
    /**
     * @var Xur
     */
    private $xur;

    /**
     * Create a new command instance.
     *
     * @param Xur $xur
     */
    public function __construct(Xur $xur)
    {
        parent::__construct();
        $this->xur = $xur;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $inventory = $this->xur->getInventory();

        if ($this->option('debug')) {
            $this->line('Dumping attachments');
            dd($inventory);
        }

        (new \GuzzleHttp\Client())->post(
            config('services.destiny.xur_slack_web_hook'),
            [
                'json' => [
                    'text'        => collect(config('services.destiny.xur_messages'))->random(),
                    'attachments' => $inventory,
                    'username'    => 'Xûr',
                    'icon_url'    => 'https://www.bungie.net/common/destiny2_content/icons/801c07dc080b79c7da99ac4f59db1f66.jpg',
                ],
            ]
        );
    }
}
