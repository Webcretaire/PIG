<?php

namespace PIG\Tests;

use PHPUnit\Framework\TestCase;
use PIG\ICS;

/**
 * Class RouterTest
 *
 * @author Julien EMMANUEL <JuEm0406@gmail.com>
 * @package DiggyRouter\Tests
 */
class ICSTest extends TestCase
{
    /**
     * @covers ICS
     */
    public function testICS()
    {
        $ics = new ICS();
        $ics
            ->createICS(__DIR__ . '/../resources/test.ics')
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
            ->saveICS();

        // Need to replace new lines, otherwise depending on the line separator of this file, the test could fail
        $desiredICS = $this->killNewLines("BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//FR
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
END:VCALENDAR");

        $this->assertEquals(
            $desiredICS,
            $this->killNewLines(file_get_contents(__DIR__ . '/../resources/test.ics'))
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