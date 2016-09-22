<?php

namespace MS\Sentry\Monitor\Service;

use MS\Sentry\Monitor\Model\SentryRequest;
use GuzzleHttp\Client;

class SentryClient
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param SentryRequest $sentryRequest
     * @param string        $endpoint
     *
     * @return array
     */
    public function get(SentryRequest $sentryRequest, $endpoint)
    {
        $stream = $this
            ->client
            ->get($sentryRequest->getBaseUrl() . $endpoint, ['auth' =>  [$sentryRequest->getApiKey(), '']])
            ->getBody()
            ->getContents();

        return json_decode($stream, true);
    }
}
