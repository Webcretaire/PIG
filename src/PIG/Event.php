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
     * @var string
     */
    private $debut;

    /**
     * @var string
     */
    private $fin;

    /**
     * @var string
     */
    private $titre;

    /**
     * @var string
     */
    private $lieu;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $timezone;

    public function __construct(
        string $debut,
        string $fin,
        string $titre,
        string $lieu = '',
        string $description = '',
        string $timezone = ''
    )
    {
        $this->debut       = $debut;
        $this->fin         = $fin;
        $this->titre       = $titre;
        $this->lieu        = $lieu;
        $this->description = $description;
        $this->timezone    = $timezone;
    }

    public function __toString(): string
    {
        return
            "BEGIN:VEVENT" . PHP_EOL .
            $this->dateLimit("START", $this->debut) . PHP_EOL .
            $this->dateLimit("END", $this->fin) . PHP_EOL .
            "SUMMARY:" . Formatter::text($this->titre) . PHP_EOL .
            "LOCATION:" . Formatter::text($this->lieu) . PHP_EOL .
            "DESCRIPTION:" . Formatter::text($this->description) . PHP_EOL .
            "END:VEVENT" . PHP_EOL;
    }

    private function dateLimit(string $type, string $date)
    {
        return "DT" . $type . (!empty($this->timezone) ? (';TZID=' . $this->timezone) : '') . ":" .
            Formatter::date($date) . (empty($this->timezone) ? 'Z' : '');
    }
}