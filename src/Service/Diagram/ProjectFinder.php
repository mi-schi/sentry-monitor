<?php

namespace MS\Sentry\Monitor\Service\Diagram;

use Doctrine\DBAL\Connection;

class ProjectFinder
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
     * @param string $project
     *
     * @return array
     */
    public function getProjects($organisation, $project)
    {
        if (false === empty($project)) {
            return [$project];
        }

        $projects = $this->connection->fetchAll(
            'SELECT project FROM events WHERE organisation = ? GROUP BY project',
            [$organisation]
        );

        return array_column($projects, 'project');
    }
}
