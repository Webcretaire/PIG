<?php

namespace PIG;

/**
 * Class ICS
 *
 * Main class, that generates the ICS file
 * @author  Julien EMMANUEL <contact@julien-emmanuel.com>
 * @package PIG
 */
class ICS
{
    /**
     * @var resource File to be written on disk
     */
    private $icsFile;

    /**
     * @var string Path of the file
     */
    private $icsPath;

    /**
     * @var string Timezone name (TZID)
     */
    private $timezone;

    /**
     * @var array List of events to put in the ICS file
     */
    private $events = [];

    public function __construct(string $timezone = '')
    {
        $this->timezone = $timezone;
    }

    /**
     * Defines the timezone to use
     *
     * @param string $timezone
     * @return $this
     */
    public function setTimezone(string $timezone)
    {
        $this->timezone = $timezone;
        return $this;
    }

    /**
     * Adds an event to the ICS file
     *
     * @param string $start
     * @param string $end
     * @param string $title
     * @param string $location
     * @param string $description
     * @return ICS
     */
    public function addEvent(
        string $start,
        string $end,
        string $title,
        string $location = '',
        string $description = ''
    ): ICS
    {
        $this->events[] = new Event($start, $end, $title, $location, $description, $this->timezone);

        return $this;
    }

    /**
     * Saves the file on disk
     *
     * All events are written to the file which is then closed
     * @param string $file Path to write to
     * @throws PigException
     */
    public function saveICS(string $file)
    {
        $this->icsPath = $file;
        $this->icsFile = fopen($file, 'w');

        $this->write("BEGIN:VCALENDAR" . PHP_EOL);
        $this->write("VERSION:2.0" . PHP_EOL);
        $this->write("PRODID:-//hacksw/handcal//NONSGML v1.0//FR" . PHP_EOL);

        if (!empty($this->timezone))
            $this->write($this->extractTimezone());

        foreach ($this->events as $event)
            $this->write((string) $event);

        $this->write("END:VCALENDAR");

        if (!fclose($this->icsFile))
            throw new PigException("IO exception : could not close file " . $this->icsPath);
    }

    /**
     * Adds error handling to fwrite function
     *
     * @param string $text The text to write
     * @throws PigException
     */
    private function write(string $text)
    {
        if (fwrite($this->icsFile, $text) === FALSE)
            throw new PigException("IO exception : could not write to file " . $this->icsPath);
    }

    /**
     * Extracts the timezone from an ics template
     * @return string Timezone data
     */
    private function extractTimezone() {
        $out = '';
        $path = __DIR__ . '/../../timezones/' . $this->timezone . '.ics';

        if (is_file($path)) {
            $out .= "BEGIN:VTIMEZONE";
            $str        = file_get_contents($path);
            $start      = 'BEGIN:VTIMEZONE';
            $end        = 'END:VTIMEZONE';
            $startIndex = strpos($str, $start) + strlen($start);
            $out .= substr($str, $startIndex, strrpos($str, $end) - $startIndex);

            $out .= "END:VTIMEZONE" . PHP_EOL;
        }

        return $out;
    }
}