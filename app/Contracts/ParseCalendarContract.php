<?php


namespace App\Contracts;

interface ParseCalendarContract
{

    public function parseCalendar();

    public function getParsedCalendar();
    public function setParsedCalendar();

    public function getRawCalendar();
    public function setRawCalendar();



}
