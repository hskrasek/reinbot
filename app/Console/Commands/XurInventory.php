<?php

namespace App\Console\Commands;

use App\Services\Destiny\Xur;
use Bugsnag\Client as Bugsnag;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

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
     * @var Bugsnag
     */
    private $bugsnag;

    /**
     * Create a new command instance.
     *
     * @param Xur $xur
     * @param Bugsnag $bugsnag
     */
    public function __construct(Xur $xur, Bugsnag $bugsnag)
    {
        parent::__construct();
        $this->xur = $xur;
        $this->bugsnag = $bugsnag;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $times = 5;
        beginning:
        try {
            $inventory = $this->xur->getInventory();
        } catch (ClientException $exception) {
            if (!$times) {
                $this->bugsnag->leaveBreadcrumb(
                    'Failed to refresh tokens before getting Xur inventory',
                    \Bugsnag\Breadcrumbs\Breadcrumb::ERROR_TYPE
                );
                throw $exception;
            }

            $times--;
            Artisan::call('destiny:refresh-tokens');
            goto beginning;
        }

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
