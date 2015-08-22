<?php

namespace EnlightenedDC\Sentry\Monitor\Api;

use GuzzleHttp\Client as GuzzleClient;
use EnlightenedDC\Sentry\Monitor\Models\Organisation;
use EnlightenedDC\Sentry\Monitor\Models\Project;

/**
 * Class Client
 */
class Client
{
    /**
     * @var GuzzleClient
     */
    private $guzzleClient;

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        $this->guzzleClient = new GuzzleClient();
    }

    /**
     * @param Organisation $organisation
     *
     * @return array
     */
    public function getProjects(Organisation $organisation)
    {
        return $this->guzzleClient->get($organisation->getProjectsUrl(), [
            'verify' => false,
            'auth' =>  [$organisation->getApiKey(), '']
        ])->getBody()->getContents();
    }

    /**
     * @param Project $project
     *
     * @return array
     */
    public function getExceptions(Project $project)
    {
        return $this->guzzleClient->get($project->getExceptionsUrl(), [
            'verify' => false,
            'auth' =>  [$project->getOrganisation()->getApiKey(), '']
        ])->getBody()->getContents();
    }
}
