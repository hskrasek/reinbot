<?php declare(strict_types=1);

namespace App\Slack;

use App\Slack\Builder\AbstractBuilder;
use Illuminate\Contracts\Container\Container;

class BlockBuilder
{
    /**
     * @var array
     */
    private $milestoneMapping;

    /**
     * @var Container
     */
    private $container;

    public function __construct(array $milestoneMapping, Container $container)
    {
        $this->milestoneMapping = $milestoneMapping;
        $this->container = $container;
    }

    /**
     * @param array $milestones
     *
     * @return array|Block[]
     */
    public function buildForMilestones(array $milestones): array
    {
        $blocks = [];

        foreach ($milestones as $hash => $milestone) {
            $builderClass = array_get($this->milestoneMapping, $hash);

            if ($builderClass === null) {
                \Bugsnag::notifyError(
                    'UnmappedMilestoneException',
                    'A milestone has been encountered that is not currently mapped',
                    function ($report) use ($hash) {
                        $report->setSeverity('info');
                        $report->setMetaData([
                            'milestone' => [
                                'hash' => $hash,
                            ]
                        ]);
                    }
                );
                continue;
            }

            if ($builderClass === false) {
                continue;
            }

            /** @var AbstractBuilder $builder */
            $builder = $this->container->make($builderClass);

            $milestoneBlocks = $builder->buildBlocks($hash, $milestone);

            foreach ($milestoneBlocks as $milestoneBlock) {
                $blocks[] = $milestoneBlock;
            }
        }

        return $blocks;
    }
}
