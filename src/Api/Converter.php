<?php

namespace EnlightenedDC\Sentry\Monitor\Api;

use EnlightenedDC\Sentry\Monitor\Models\Exception;
use EnlightenedDC\Sentry\Monitor\Models\Organisation;
use EnlightenedDC\Sentry\Monitor\Models\Project;

/**
 * Class Converter
 *
 * @package EnlightenedDC\Sentry\Monitor\Api
 */
class Converter
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
     * @param Organisation $organisation
     *
     * @return array
     */
    public function getProjects(Organisation $organisation)
    {
        $projects = [];
        $g = $this->client->getProjects($organisation);

        foreach ($g as $projectData) {

            if (in_array($projectData['slug'], $organisation->getProjectBlacklist())) {
                continue;
            }

            $project = new Project();
            $project->setId($projectData['slug']);
            $project->setName($projectData['name']);
            $project->setOrganisation($organisation);
            $project->setExceptions($this->getExceptions($project));

            $projects[] = $project;
        }

        return $projects;
    }

    /**
     * @param Project $project
     *
     * @return array
     */
    private function getExceptions(Project $project)
    {
        $exceptions = [];

        foreach ($this->client->getExceptions($project) as $exceptionData) {
            $exception = new Exception();
            $exception->setCount($exceptionData['count']);
            $exception->setDatetime(new \DateTime($exceptionData['lastSeen']));

            $exceptions[] = $exception;
        }

        return $exceptions;
    }
}
