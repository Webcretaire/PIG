<?php

namespace PIG\Tests;

use PHPUnit\Framework\TestCase;
use PIG\ICS;

/**
 * Class RouterTest
 *
 * @author Julien EMMANUEL <contact@julien-emmanuel.com>
 * @package DiggyRouter\Tests
 */
class ICSTest extends TestCase
{
    /**
     * @covers ICS::createICS
     * @covers ICS::addEvent
     * @covers ICS::saveICS
     * @throws \PIG\PigException
     */
    public function testICS()
    {
        $dir = __DIR__ . '/../resources';

        if(!is_dir($dir)) mkdir($dir);

        $ics = new ICS('Europe/Paris');
        $ics
            ->addEvent(
                '2017-10-06 20:15:00',
                '2017-10-07 02:00:00',
                'Awesome party',
                'At my house',
                'Amazing party, 

with friends and all'
            )
            ->addEvent(
                '2017-10-07 15:00:42',
                '2017-10-07 02:00:00',
                'House cleaning ...'
            )
            ->saveICS($dir . '/test.ics');

        // Need to replace new lines, otherwise depending on the line separator of this file, the test could fail
        $desiredICS = "BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//FR
BEGIN:VTIMEZONE
TZID:/citadel.org/20070227_1/Europe/Paris
X-LIC-LOCATION:Europe/Paris
BEGIN:DAYLIGHT
TZOFFSETFROM:+0100
TZOFFSETTO:+0200
TZNAME:CEST
DTSTART:19700329T020000
RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU
END:DAYLIGHT
BEGIN:STANDARD
TZOFFSETFROM:+0200
TZOFFSETTO:+0100
TZNAME:CET
DTSTART:19701025T030000
RRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU
END:STANDARD
END:VTIMEZONE
BEGIN:VEVENT
DTSTART:20171006T201500Z
DTEND:20171007T020000Z
SUMMARY:Awesome party
LOCATION:At my house
DESCRIPTION:Amazing party\, \\n\\nwith friends and all
END:VEVENT
BEGIN:VEVENT
DTSTART:20171007T150042Z
DTEND:20171007T020000Z
SUMMARY:House cleaning ...
LOCATION:
DESCRIPTION:
END:VEVENT
END:VCALENDAR";

        $this->assertEquals(
            $this->killNewLines($desiredICS),
            $this->killNewLines(file_get_contents($dir . '/test.ics'))
        );
    }

    private function killNewLines(string $text): string
    {
        return str_replace(
            ["\r\n", "\r", "\n"],
            '<<NewLine>>',
            $text
        );
    }
}