<?php

namespace PIG;

/**
 * Class Event
 *
 * Representation of an event to be written in the ICS file
 * @author Julien EMMANUEL <contact@julien-emmanuel.com>
 * @package PIG
 */
class Event
{
    /**
     * @var \DateTime
     */
    private $start;

    /**
     * @var \DateTime
     */
    private $end;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $location;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $timezone;

    public function __construct(
        \DateTime $start,
        \DateTime $end,
        string $title,
        string $location = '',
        string $description = '',
        string $timezone = ''
    )
    {
        $this->start       = $start;
        $this->end         = $end;
        $this->title       = $title;
        $this->location    = $location;
        $this->description = $description;
        $this->timezone    = $timezone;
    }

    /**
     * String representation of an event
     *
     * To be written directly in an ICS file
     * @return string
     */
    public function __toString(): string
    {
        return
            "BEGIN:VEVENT" . PHP_EOL .
            $this->dateLimit("START", $this->start) . PHP_EOL .
            $this->dateLimit("END", $this->end) . PHP_EOL .
            "SUMMARY:" . Formatter::text($this->title) . PHP_EOL .
            "LOCATION:" . Formatter::text($this->location) . PHP_EOL .
            "DESCRIPTION:" . Formatter::text($this->description) . PHP_EOL .
            "END:VEVENT" . PHP_EOL;
    }

    /**
     * Formats a date depending on the type and timezone
     * @param string $type "START" or "END"
     * @param \DateTime $date The actual date, in string format
     * @return string The line to be written to the ICS file
     */
    private function dateLimit(string $type, \DateTime $date)
    {
        return "DT" . $type . (!empty($this->timezone) ? (';TZID=' . $this->timezone) : '') . ":" .
            Formatter::date($date) . (empty($this->timezone) ? 'Z' : '');
    }
}