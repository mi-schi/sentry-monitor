<?php

namespace EnlightenedDC\Sentry\Monitor\Models;

/**
 * Class Project
 *
 * @package EnlightenedDC\Sentry\Monitor\Models
 */
class Project
{
    const PROJECT_URL_PATTERN = '%s/api/0/projects/%s/%s/groups/';

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Organisation
     */
    private $organisation;

    /**
     * @var array
     */
    private $exceptions = [];

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return Organisation
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }

    /**
     * @param Organisation $organisation
     */
    public function setOrganisation(Organisation $organisation)
    {
        $this->organisation = $organisation;
    }

    /**
     * @return array
     */
    public function getExceptions()
    {
        return $this->exceptions;
    }

    /**
     * @param array $exceptions
     */
    public function setExceptions(array $exceptions)
    {
        $this->exceptions = $exceptions;
    }

    /**
     * @return string
     */
    public function getExceptionsUrl()
    {
        return sprintf(self::PROJECT_URL_PATTERN, $this->getOrganisation()->getUrl(), $this->getOrganisation()->getId(), $this->getId());
    }
}
