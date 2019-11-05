<?php declare(strict_types=1);

namespace App\Slack\Builder;

use App\Services\Destiny\Client;
use App\Slack\Block;

abstract class AbstractBuilder
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param int $milestoneHash
     * @param array $milestoneData
     *
     * @return array|Block[]
     */
    abstract public function buildBlocks(int $milestoneHash, array $milestoneData): array;
}
