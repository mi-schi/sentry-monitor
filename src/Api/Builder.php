<?php

namespace EnlightenedDC\Sentry\Monitor\Api;

use EnlightenedDC\Sentry\Monitor\Models\Exception;
use EnlightenedDC\Sentry\Monitor\Models\Project;

/**
 * Class Builder
 *
 * @package EnlightenedDC\Sentry\Monitor\Api
 */
class Builder
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var Converter
     */
    private $converter;

    /**
     * @param Parser    $parser
     * @param Converter $converter
     */
    public function __construct(Parser $parser, Converter $converter)
    {
        $this->parser = $parser;
        $this->converter = $converter;
    }

    /**
     * @param string $id organisation id
     *
     * @return array
     */
    public function getSequences($id)
    {
        $organisation = $this->parser->getOrganisation($id);
        $organisation->setProjects($this->converter->getProjects($organisation));

        $sequences = [];

        /** @var Project $project */
        foreach ($organisation->getProjects() as $project) {
            $sequence = [];
            $sequence['label'] = $project->getName();
            $sequence['data'] = $this->getPoints($project->getExceptions());

            $sequences[] = $sequence;
        }

        return $sequences;
    }

    /**
     * @param array $exceptions
     *
     * @return array
     */
    private function getPoints(array $exceptions)
    {
        $points = [];

        /** @var Exception $exception */
        foreach ($exceptions as $exception) {
            $points[] = [
                $exception->getDatetime()->getTimestamp() * 1000,
                $exception->getCount()
            ];
        }

        return $points;
    }
}
