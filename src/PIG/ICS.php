<?php

namespace PIG;

/**
 * Class ICS
 *
 * Main class, that generates the ICS file
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
     * @var string Timezone name
     */
    private $timezone = '';

    /**
     * Creates the ICS file
     *
     * @param string $file
     * @param string $timezone
     * @return ICS
     * @throws PigException
     */
    public function createICS(string $file, string $timezone = ''): ICS
    {
        $this->timezone = $timezone;
        $this->icsPath = $file;
        $this->icsFile = fopen($file, 'w');

        $this->write("BEGIN:VCALENDAR" . PHP_EOL);
        $this->write("VERSION:2.0" . PHP_EOL);
        $this->write("PRODID:-//hacksw/handcal//NONSGML v1.0//FR" . PHP_EOL);
        if (!empty($timezone)) {
            $this->write("BEGIN:VTIMEZONE" . PHP_EOL);
            $this->write("TZID:$timezone" . PHP_EOL);
            $this->write("X-LIC-LOCATION:$timezone" . PHP_EOL);
            if ($timezone == 'Europe/Paris') {
                $this->write("BEGIN:DAYLIGHT" . PHP_EOL);
                $this->write("TZOFFSETFROM:+0100" . PHP_EOL);
                $this->write("TZOFFSETTO:+0200" . PHP_EOL);
                $this->write("TZNAME:CEST" . PHP_EOL);
                $this->write("DTSTART:19700329T020000" . PHP_EOL);
                $this->write("RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU" . PHP_EOL);
                $this->write("END:DAYLIGHT" . PHP_EOL);
                $this->write("BEGIN:STANDARD" . PHP_EOL);
                $this->write("TZOFFSETFROM:+0200" . PHP_EOL);
                $this->write("TZOFFSETTO:+0100" . PHP_EOL);
                $this->write("TZNAME:CET" . PHP_EOL);
                $this->write("DTSTART:19701025T030000" . PHP_EOL);
                $this->write("RRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU" . PHP_EOL);
                $this->write("END:STANDARD" . PHP_EOL);
            }
            $this->write("END:VTIMEZONE" . PHP_EOL);
        }

        return $this;
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
     * @param string $debut
     * @param string $fin
     * @param string $titre
     * @param string $lieu
     * @param string $description
     * @return ICS
     * @throws PigException
     */
    public function addEvent(string $debut, string $fin, string $titre, string $lieu = '', string $description = ''): ICS
    {
        $this->write("BEGIN:VEVENT" . PHP_EOL);
        $this->write("DTSTART" .
            (!empty($this->timezone) ? (';TZID=' . $this->timezone) : '') . ":" . // Timezone handling
            $this->formatDate($debut) . (empty($this->timezone) ? 'Z' : '') . PHP_EOL);
        $this->write("DTEND" .
            (!empty($this->timezone) ? (';TZID=' . $this->timezone) : '') . ":" . // Timezone handling
            $this->formatDate($fin) . (empty($this->timezone) ? 'Z' : '') . PHP_EOL);
        $this->write("SUMMARY:" . $this->formatText($titre) . PHP_EOL);
        $this->write("LOCATION:" . $this->formatText($lieu) . PHP_EOL);
        $this->write("DESCRIPTION:" . $this->formatText($description) . PHP_EOL);
        $this->write("END:VEVENT" . PHP_EOL);

        return $this;
    }

    /**
     * Saves the file once it has been correctly filled with all events
     *
     * @throws PigException
     */
    public function saveICS()
    {
        $this->write("END:VCALENDAR");
        if (!fclose($this->icsFile))
            throw new PigException("IO exception : could not close file " . $this->icsPath);
    }

    /**
     * Formats a date to be used in an ICS file
     *
     * @param string $date
     * @return string
     */
    private function formatDate(string $date): string
    {
        return date('Ymd\THis', strtotime($date));
    }

    /**
     * Formats a text to be used in an ICS file
     *
     * @param string $text
     * @return string
     */
    private function formatText(string $text): string
    {
        return preg_replace(
            '/([\,;])/',
            '\\\$1',
            (str_replace(
                ["\r\n", "\r", "\n"],
                '\n',
                $text)
            )
        );
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
}