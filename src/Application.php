<?php

namespace MS\Sentry\Monitor;

use MS\Sentry\Monitor\Migration\EventTable;
use Silex\Application as BaseApplication;
use Silex\Provider\DoctrineServiceProvider;

/**
 * @package MS\Sentry\Monitor
 */
class Application extends BaseApplication
{
    /**
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        parent::__construct($values);

        $this->register(new DoctrineServiceProvider, ['db.options' => [
            'dbname' => 'events',
            'driver' => 'pdo_sqlite',
            'path' => getenv('HOME') . '/.events.db',
        ]]);

        $eventTable = new EventTable($this['db']);
        $eventTable->create();
    }
}
