<?php

namespace MS\Sentry\Monitor\Service\Diagram;

use Doctrine\DBAL\Connection;

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
     * @param array  $projects
     * @param string $days
     * @param string $scale
     *
     * @return array
     */
    public function getSequences(array $projects, $days, $scale)
    {
        if (false === filter_var($days, FILTER_VALIDATE_INT)) {
            throw new \InvalidArgumentException(sprintf('The days parameter "%s" should be an integer', $days));
        }

        if (false === in_array($scale, ['day', 'hour'])) {
            throw new \InvalidArgumentException(sprintf('The scale parameter "%s" should be "hour" or "day"', $scale));
        }

        $data = [];
        $datetimeFormat = $this->getDatetimeFormat($scale);
        $points = $this->getZeroSizedPoints($days, $scale);

        foreach ($projects as $project) {
            $data[] = [
                'label' => $project,
                'data' => $this->getFilledPoints($points, $datetimeFormat, $project)
            ];
        }

        return $data;
    }

    /**
     * @param string $days
     * @param string $scale
     *
     * @return array
     */
    private function getZeroSizedPoints($days, $scale)
    {
        $lastPoint = mktime(date('H'), 0, 0);
        $step = 3600;

        if ('day' === $scale) {
            $lastPoint = mktime(0, 0, 0);
            $step = $step * 24;
        }

        $firstPoint = strtotime(sprintf('-%s day', $days), $lastPoint);
        $points = [];

        foreach (range($firstPoint, $lastPoint, $step) as $point) {
            $points[] = [
                $point * 1000,
                0
            ];
        }

        return $points;
    }

    /**
     * @param string $scale
     *
     * @return string
     */
    private function getDatetimeFormat($scale)
    {
        $datetimeFormat = '%Y-%m-%d %H:00:00';

        if ('day' === $scale) {
            $datetimeFormat = '%Y-%m-%d 00:00:00';
        }

        return $datetimeFormat;
    }

    /**
     * @param array  $points
     * @param string $datetimeFormat
     * @param string $project
     *
     * @return array
     */
    private function getFilledPoints(array $points, $datetimeFormat, $project)
    {
        $events = $this->connection->fetchAll(
            'SELECT strftime(?, created) as hour, COUNT(*) as count FROM events WHERE project = ? GROUP BY hour ORDER BY hour DESC',
            [$datetimeFormat, $project]
        );

        foreach ($events as $event) {
            $timestamp = strtotime($event['hour']) * 1000;
            $key = array_search($timestamp, array_column($points, 0));

            if (false === $key) {
                break;
            }

            $points[$key] = [
                $timestamp,
                $event['count']
            ];
        }

        return $points;
    }
}
