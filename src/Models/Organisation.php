<?php

namespace EnlightenedDC\Sentry\Monitor\Models;

/**
 * Class Organisation
 */
class Organisation
{
    const ORGANISATION_URL_PATTERN = '%s/api/0/organizations/%s/projects/';

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $url;

    /**
     * @var array
     */
    private $projectBlacklist = [];

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var array
     */
    private $projects = [];

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return array
     */
    public function getProjectBlacklist()
    {
        return $this->projectBlacklist;
    }

    /**
     * @param array $projectBlacklist
     */
    public function setProjectBlacklist($projectBlacklist)
    {
        $this->projectBlacklist = $projectBlacklist;
    }

    /**
     * @return array
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * @param array $projects
     */
    public function setProjects(array $projects)
    {
        $this->projects = $projects;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public function getProjectsUrl()
    {
        return sprintf(self::ORGANISATION_URL_PATTERN, $this->getUrl(), $this->getId());
    }
}
