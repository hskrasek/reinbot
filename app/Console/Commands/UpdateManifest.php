<?php

namespace App\Console\Commands;

use App\Manifest;
use App\Services\Destiny\Client;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateManifest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'destiny:manifest {--force : Force download the manifest}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download the latest Destiny 2 Manifest';

    /**
     * @var Client
     */
    private $client;

    /**
     * Create a new command instance.
     *
     * @param Client $client
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
        $this->info('Checking for a new manifest...');

        $newManifest = $this->client->getManifest();

        if (!$this->option('force')) {
            try {
                $manifest = Manifest::whereVersion($newManifest['version'])->firstOrFail();

                $this->warn('Manifest ' . $manifest->version . ' already exists.');

                return;
            } catch (ModelNotFoundException $exception) {
            }
        }

        $this->info('Downloading new manifest. Version: ' . $newManifest['version']);

        $this->client->downloadManifest(
            array_get($newManifest, 'mobileWorldContentPaths.en')
        );

        Manifest::create(['version' => $newManifest['version']]);
    }
}
