<?php

namespace MS\Sentry\Monitor\Service\Import;

use MS\Sentry\Monitor\Model\SentryRequest;
use MS\Sentry\Monitor\Service\SentryClient;

/**
 * @package MS\Sentry\Monitor\Service\Import
 */
class ProjectCollector
{
    const PROJECTS_PATTERN = '/api/0/organizations/%s/projects/';

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
     *
     * @return array
     */
    public function getSlugs(SentryRequest $sentryRequest, $organisation)
    {
        $projects = $this
            ->sentryClient
            ->get($sentryRequest, sprintf(self::PROJECTS_PATTERN, $organisation));

        return array_column($projects, 'slug');
    }
}
