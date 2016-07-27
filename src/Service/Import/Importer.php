<?php

namespace MS\Sentry\Monitor\Service\Import;

use Doctrine\DBAL\Connection;

/**
 * @package MS\Sentry\Monitor\Service\Import
 */
class Importer
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
     * @param array  $simplifiedEvent
     */
    public function import($organisation, $project, array $simplifiedEvent)
    {
        $this->connection->executeQuery(
            'INSERT OR IGNORE INTO events (organisation, project, event_id, created) VALUES(?, ?, ?, ?)',
            [
                $organisation,
                $project,
                $simplifiedEvent['id'],
                $simplifiedEvent['created']
            ]
        );
    }
}
