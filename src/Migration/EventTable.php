<?php

namespace MS\Sentry\Monitor\Migration;

use Doctrine\DBAL\Connection;

class EventTable
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
     */
    public function create()
    {
        $createTableSql = 'CREATE TABLE IF NOT EXISTS events (
            organisation TEXT,
            project TEXT,
            event_id INTEGER,
            created TEXT,
            UNIQUE(organisation, project, event_id, created)
        )';

        $this->connection->executeQuery($createTableSql);
    }
}
