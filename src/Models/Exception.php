<?php

namespace EnlightenedDC\Sentry\Monitor\Models;

/**
 * Class Exception
 *
 * @package EnlightenedDC\Sentry\Monitor\Models
 */
class Exception
{
    /**
     * @var int
     */
    private $count;

    /**
     * @var \DateTime
     */
    private $datetime;

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * @return \DateTime
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * @param \DateTime $datetime
     */
    public function setDatetime(\DateTime $datetime)
    {
        $this->datetime = $datetime;
    }
}
