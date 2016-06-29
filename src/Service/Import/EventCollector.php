<?php

namespace EnlightenedDC\Sentry\Monitor\Service\Import;

use EnlightenedDC\Sentry\Monitor\Model\SentryRequest;
use EnlightenedDC\Sentry\Monitor\Service\SentryClient;

/**
 * @package EnlightenedDC\Sentry\Monitor\Service\Import
 */
class EventCollector
{
    const EVENTS_PATTERN = '/api/0/projects/%s/%s/events/';

    /**
     * @var SentryClient
     */
    private $sentryClient;

    /**
     * @param SentryClient $sentryClient
     */
    public function __construct(SentryClient $sentryClient)
    {
        $this->sentryClient = $sentryClient;
    }

    /**
     * @param SentryRequest $sentryRequest
     * @param string        $organisation
     * @param string        $project
     *
     * @return array
     */
    public function getSimplifiedEvents(SentryRequest $sentryRequest, $organisation, $project)
    {
        $events = $this
            ->sentryClient
            ->get($sentryRequest, sprintf(self::EVENTS_PATTERN, $organisation, $project));

        $result = [];

        foreach ($events as $event) {
            $result[] = [
                'id' => $event['id'],
                'created' => $event['dateCreated']
            ];
        }

        return $result;
    }
}
