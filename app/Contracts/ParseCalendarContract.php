<?php


namespace App\Contracts;

interface ParseCalendarContract
{
    /**
     * Parse and filter the calendar
     *
     * @param  string  $command
     * @param  string  $raw
     * @return array
     */
    public function parsedCalendar($raw);

}
