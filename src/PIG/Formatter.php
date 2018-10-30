<?php

namespace PIG;

/**
 * Class Formatter
 *
 * Static formatting functions
 * @author Julien EMMANUEL <contact@julien-emmanuel.com>
 * @package PIG
 */
class Formatter
{
    /**
     * Formats a date to be used in an ICS file
     *
     * @param \DateTime $date
     * @return string
     */
    public static function date(\DateTime $date): string
    {
        return $date->format('Ymd\THis');
    }

    /**
     * Formats a text to be used in an ICS file
     *
     * @param string $text
     * @return string
     */
    public static function text(string $text): string
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