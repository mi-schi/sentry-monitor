<?php

namespace EnlightenedDC\Sentry\Monitor\Service\Diagram;

use Doctrine\DBAL\Connection;

/**
 * @package EnlightenedDC\Sentry\Monitor\Diagram
 */
class SequenceProvider
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $organisation
     * @param string $days
     * @param string $scale
     *
     * @return array
     */
    public function getSequences($organisation, $days, $scale)
    {
        if (false === filter_var($days, FILTER_VALIDATE_INT)) {
            throw new \InvalidArgumentException(sprintf('The days parameter "%s" should be an integer', $days));
        }

        if (false === in_array($scale, ['day', 'hour'])) {
            throw new \InvalidArgumentException('The scale parameter "%s" should be "hour" or "day"');
        }

        $datetimeFormat = '%Y-%m-%d %H:00:00';

        if ('day' === $scale) {
            $datetimeFormat = '%Y-%m-%d 00:00:00';
        }

        return $this->getDataSequences($organisation, $days, $datetimeFormat);
    }

    /**
     * @param string $organisation
     * @param int    $days
     * @param string $datetimeFormat
     *
     * @return array
     */
    private function getDataSequences($organisation, $days, $datetimeFormat)
    {
        $projects = $this->connection->fetchAll('SELECT project FROM events WHERE organisation = ? GROUP BY project', [$organisation]);
        $data = [];

        foreach (array_column($projects, 'project') as $project) {
            $events = $this->connection->fetchAll('SELECT strftime(?, created) as hour, COUNT(*) as count FROM events WHERE project = ? GROUP BY hour ORDER BY hour DESC', [$datetimeFormat, $project]);
            $points = [];

            foreach ($events as $event) {
                $timestamp = strtotime($event['hour']);

                if (strtotime(sprintf('-%s day', $days)) > $timestamp) {
                    continue;
                }

                $points[] = [
                    $timestamp * 1000,
                    $event['count']
                ];
            }

            $sequence = [
                'label' => $project,
                'data' => $points
            ];

            $data[] = $sequence;
        }

        return $data;
    }
}
