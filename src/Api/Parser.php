<?php

namespace EnlightenedDC\Sentry\Monitor\Api;

use EnlightenedDC\Sentry\Monitor\Exceptions\OrganisationNotFoundException;
use EnlightenedDC\Sentry\Monitor\Models\Organisation;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Parser
 */
class Parser
{
    /**
     * @var array
     */
    private $organisations;

    /**
     * @param string $config
     */
    public function __construct($config)
    {
        $organisations = Yaml::parse(file_get_contents($config));

        foreach ($organisations['api'] as $id => $organisationData) {
            $organisation = new Organisation();
            $organisation->setId($id);
            $organisation->setUrl($organisationData['url']);
            $organisation->setProjectBlacklist($organisationData['projectBlacklist']);
            $organisation->setApiKey($organisationData['apiKey']);

            $this->organisations[] = $organisation;
        }
    }

    /**
     * @param string $id
     *
     * @return Organisation
     * @throws OrganisationNotFoundException
     */
    public function getOrganisation($id)
    {
        /** @var Organisation $organisation */
        foreach ($this->organisations as $organisation) {
            if ($id === $organisation->getId()) {
                return $organisation;
            }
        }

        throw new OrganisationNotFoundException;
    }

    /**
     * @return array
     */
    public function getOrganisations()
    {
        return $this->organisations;
    }
}
