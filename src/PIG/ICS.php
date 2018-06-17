<?php

namespace PIG;

/**
 * Class ICS
 *
 * @package PIG
 */
class ICS
{
    /**
     * @var resource
     */
    private $icsFile;

    /**
     * @var string
     */
    private $timezone = '';

    /**
     * @param string $file
     * @param string $timezone
     * @return ICS
     */
    public function createICS(string $file, string $timezone = ''): ICS
    {
        $this->timezone = $timezone;

        $this->icsFile = fopen($file, 'w');

        fwrite($this->icsFile, "BEGIN:VCALENDAR\n");
        fwrite($this->icsFile, "VERSION:2.0\n");
        fwrite($this->icsFile, "PRODID:-//hacksw/handcal//NONSGML v1.0//FR\n");

        return $this;
    }

    /**
     * @param string $timezone
     * @return $this
     */
    public function setTimezone(string $timezone)
    {
        $this->timezone = $timezone;
        return $this;
    }

    /**
     * @param string $debut
     * @param string $fin
     * @param string $titre
     * @param string $lieu
     * @param string $description
     * @return ICS
     */
    public function addEvent(string $debut, string $fin, string $titre, string $lieu = '', string $description = ''): ICS
    {
        fwrite($this->icsFile, "BEGIN:VEVENT\n");
        fwrite($this->icsFile, "DTSTART;" .
            (!empty($this->timezone) ? ('TZID=' . $this->timezone) : '') . ":" . // Timezone handling
            $this->formatDate($debut) . (empty($this->timezone) ? 'Z' : '') . "\n");
        fwrite($this->icsFile, "DTEND;" .
            (!empty($this->timezone) ? ('TZID=' . $this->timezone) : '') . ":" . // Timezone handling
            $this->formatDate($fin) . (empty($this->timezone) ? 'Z' : '') . "\n");
        fwrite($this->icsFile, "SUMMARY:" . $this->formatText($titre) . "\n");
        fwrite($this->icsFile, "LOCATION:" . $this->formatText($lieu) . "\n");
        fwrite($this->icsFile, "DESCRIPTION:" . $this->formatText($description) . "\n");
        fwrite($this->icsFile, "END:VEVENT\n");

        return $this;
    }

    public function saveICS()
    {
        fwrite($this->icsFile, "END:VCALENDAR");
        fclose($this->icsFile);
    }

    /**
     * @param string $date
     * @return string
     */
    private function formatDate(string $date): string
    {
        return date('Ymd\THis', strtotime($date));
    }

    /**
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

}