<?php


namespace App\Repository;

use App\Contracts\ParseCalendarContract;
use ICal\ICal;


// ToDo handle hourly leave

class ParseCalendar implements ParseCalendarContract
{


    // ToDo move arrays to constructor
    private $rawCalendar;
    private $parsedCalendar;
    private $filteredCalendar;
    private $results;
    private $wrongTokens;
    private $wrongAbsenceTypes;
    private $calendarEvents;
    private $test;

    public function __construct()
    {
        $this->parsedCalendar = [];
        $this->filteredCalendar = [];
        $this->wrongAbsenceTypes = [
            "Homeoffice",
            "Feiertag",
            "Einheit",
            'Arztbesuch',
            "Vertretung:",
        ];

        $this->wrongTokens = [
            '-',
            '+',
            'Tag),',
            'Tage)',
            'Einheit',
        ];

        $this->calendarEvents = array(
            "employee" => array(),
            "absence_id" => "absence_id",
            "absence_begin" => "absence_begin",
            "absence_end" => "absence_end",
            "created" => "created",
        );

        $this->results = array(
            "first_name" => "first_name",
            "last_name" => "last_name",
            "absence_type" => "Homeoffice",
            "substitutes" => array(
                0 => array(
                    'first_name' => 'first_name',
                    'last_name' => 'last_name',
                ),
                1 => array(
                    'first_name' => 'first_name',
                    'last_name' => 'last_name',
                ),
                2 => array(
                    'first_name' => 'first_name',
                    'last_name' => 'last_name',
                ),
            )
        );

        $this->test = array(
            0 => array(
                'first_name' => 'first_name',
                'last_name' => 'last_name',
            ),
            1 => array(
                'first_name' => 'first_name',
                'last_name' => 'last_name',
            ),
            2 => array(
                'first_name' => 'first_name',
                'last_name' => 'last_name',
            ));
    }

    /**
     * @param mixed $rawCalendar
     */


    public function parsedCalendar($raw)
    {
        $this->rawCalendar = $raw;
        $this->parseData();
        $this->extractEvents();
        return $this->filteredCalendar;

    }

    private function parseData()
    {
        $ical = new ICal($this->rawCalendar, array(
            'defaultSpan' => 2,     // Default value
            'defaultTimeZone' => 'UTC',
            'defaultWeekStart' => 'MO',  // Default value
            'disableCharacterReplacement' => false, // Default value
            'filterDaysAfter' => null,  // Default value
            'filterDaysBefore' => null,  // Default value
            'skipRecurrence' => false, // Default value
        ));
        $this->parsedCalendar = $ical->events();;
    }

    private function extractEvents()
    {
        $eventsFiltered = array_filter($this->parsedCalendar, array($this, 'filterSummary'));
        foreach ($eventsFiltered as $event) {
            $summary = $event->summary;
            $this->calendarEvents[] = ["employee" => $this->extractEventDetails($summary),
                "absence_id" => $this->extractUid($event->uid),
                "absence_begin" => $event->dtstart,
                "absence_end" => $event->dtend,
                "created" => $event->created];
        }
        $this->filteredCalendar = array_filter($this->calendarEvents, array($this, "filterEvents"));
    }

    private function extractEventDetails($inputName)
    {
        $parts = explode(' ', $inputName);
        $partsFiltered = array_values(array_filter($parts, array($this, 'filterParts')));
        if (array_key_exists(3, $partsFiltered)) {
            $this->results = ["first_name" => $partsFiltered[0],
                "last_name" => $partsFiltered[1],
                "absence_type" => $partsFiltered[3],
                "substitutes" => $this->extractSubstitutes($partsFiltered)];
        }
        //print_r($this->results);
        return $this->results;
    }

    private function extractUid($uidInput)
    {
        $uidString = strval($uidInput);
        $vacationId = strstr($uidString, '@', true);
        return $this->splitString($vacationId);
    }

    private function extractSubstitutes($parts)
    {
        $substitutes = [
            0 => array(
                "first_name" => "first_name",
                "last_name" => "last_name",
            ),
            1 => array(
                "first_name" => "first_name",
                "last_name" => "last_name",
            ),
            2 => array(
                "first_name" => "first_name",
                "last_name" => "last_name",
            )
        ];
        for ($j = 0; $j < count($parts); $j++) {
            if ($parts[$j] == 'Vertretung:') {
                $i = 0;
                for ($k = $j + 1; $k < count($parts) - 1; $k++) {
                    $substitutes[$i]['first_name'] = $parts[$k];
                    $substitutes[$i]['last_name'] = $parts[$k + 1] . "\n";
                    $i++;
                    $k++;
                }
            }
        }
        return $substitutes;
    }

    private function filterEvents($events)
    {
        if (isset($events['employee']['absence_type'])) {
            return !in_array($events['employee']['absence_type'], $this->wrongAbsenceTypes);
        }
        return false;
    }

    private function filterParts($part)
    {
        return !in_array($part, $this->wrongTokens);
    }

    private function filterSummary($event)
    {
        if (strpos($event->summary, 'Homeoffice') > 0) {
            return false;
        }
        return true;
    }

    private function splitString($inputString)
    {
        // seperate the id from the 'urlaub'
        $parts = preg_split("/(,?\s+)|((?<=[a-z])(?=\d))|((?<=\d)(?=[a-z]))/i", $inputString);
        return intval($parts[1]);
    }
}


/*  */
