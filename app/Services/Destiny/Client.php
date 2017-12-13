<?php namespace App\Services\Destiny;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Str;
use Psr\Log\LoggerInterface;
use function GuzzleHttp\Psr7\stream_for;

class Client
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $log;

    public function __construct(GuzzleClient $client, LoggerInterface $log)
    {
        $this->client = $client;
        $this->log    = $log;
    }

    /**
     * @param string $endpoint
     *
     * @return array
     * @throws \Exception
     */
    public function get(string $endpoint): array
    {
        $response = $this->client->get(Str::endsWith($endpoint, '/') ? $endpoint : $endpoint . '/');

        $response = json_decode((string)$response->getBody(), true);

        if (array_get($response, 'ErrorCode') !== 1) {
            $this->log->error('destiny.api.error', [
                'message' => array_get($response, 'Message'),
                'status'  => array_get($response, 'ErrorStatus'),
            ]);

            throw new \Exception('There was an error calling the Destiny 2 API.');
        }

        return array_get($response, 'Response', []);
    }

    public function getManifest(): array
    {
        return $this->get('Manifest/');
    }

    public function downloadManifest(string $manifestPath)
    {
        $this->client->get(
            $manifestPath,
            ['sink' => stream_for(fopen($tempManifest = storage_path('app/manifest/temp_manifest.content'), 'w'))]
        );
        $manifest = new \ZipArchive;
        $manifest->open($tempManifest);
        $manifest->extractTo(storage_path('app/uncompressed_manifest'));
        $manifest->close();

        // Cleanup temp manifest
        unlink(\Storage::path(last(\Storage::allFiles('manifest'))));

        \Storage::delete('manifest.sqlite');
        \Storage::move(head(\Storage::allFiles('uncompressed_manifest')), 'manifest.sqlite');
    }

    public function getMilestones()
    {
        return collect($this->get('Milestones/'));
    }

    public function getMilestoneContent($milestoneHash): array
    {
        return $this->get('Milestones/' . $milestoneHash . '/Content/');
    }

    public function getItemDefinition($itemHash): array
    {
        return $this->get('Manifest/DestinyInventoryItemDefinition/' . $itemHash . '/');
    }

    public function getActivityDefinition($activityHash): array
    {
        return $this->get('Manifest/DestinyActivityDefinition/' . $activityHash . '/');
    }

    public function getObjectiveDefinition($objectiveHash): array
    {
        return $this->get('Manifest/DestinyObjectiveDefinition/' . $objectiveHash . '/');
    }

    public function getModifierDefinition($modifierHash): array
    {
        return $this->get('Manifest/DestinyActivityModifierDefinition/' . $modifierHash . '/');
    }
}
