<?php


namespace App\Repository;

use ICal\ICal;


// ToDo handle hourly leave
// Rework naming for methods / varaibles

class ParseCalendarRepository
{

    private $rawCalendar;
    private $parsedCalendar;
    private $filteredCalendar;
    private $wrongAbsenceTypes;

    public function __construct()
    {
        $this->parsedCalendar = [];
        $this->filteredCalendar = [];
        $this->wrongAbsenceTypes = [
            "Homeoffice",
            "Feiertag",
            "Einheit",
            'Arztbesuch',
            "Tag)",
            "-",
        ];
    }

    /**
     * @return mixed
     */
    public function getRawCalendar()
    {
        return $this->rawCalendar;
    }

    /**
     * @param mixed $rawCalendar
     */
    public function setRawCalendar($rawCalendar): void
    {
        $this->rawCalendar = $rawCalendar;
    }

    /**
     * @return array
     */
    public function getParsedCalendar(): array
    {
        return $this->parsedCalendar;
    }

    /**
     * @param array $parsedCalendar
     */
    public function setParsedCalendar(array $parsedCalendar): void
    {
        $this->parsedCalendar = $parsedCalendar;
    }

    public function parsedCalendar()
    {
        $this->parseData();
        $this->extractEvents();
        return collect($this->filteredCalendar);
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
        $calendarEvents = [];
        foreach ($this->parsedCalendar as $event) {
            $summary = $event->summary;
            $calendarEvents[] = ["employee" => $this->extractEventDetails($summary),
                "absence_id" => $this->extractUid($event->uid),
                "absence_begin" => $event->dtstart,
                "absence_end" => $event->dtend,
                "created" => $event->created];
        }

        $this->filteredCalendar = array_filter($calendarEvents, array($this, "filterEvents"));

    }

    private function extractEventDetails($inputName)
    {
        $results = [];
        $parts = explode(' ', $inputName);

        if (array_key_exists(3, $parts)) {
            $results = ["first_name" => $parts[0],
                "last_name" => $parts[1],
                "absence_type" => $parts[3],
                "substitutes" => $this->extractSubstitutes($parts)];
        }
        return $results;
    }

    private function extractUid($uidInput)
    {
        $uidString = strval($uidInput);
        $vacationId = strstr($uidString, '@', true);
        return $this->splitString($vacationId);
    }

    private function extractSubstitutes($parts)
    {
        $substitutes = [];
        if (array_key_exists(6, $parts)) {
            if ($parts[6] == 'Vertretung:') {
                $substitutes["first_name_01"] = $parts[7];
                $substitutes["last_name_01"] = $parts[8];
            }
        }
        if (array_key_exists(9, $parts)) {
            if ($parts[9] == '+') {
                $results["first_name_02"] = $parts[10];
                $results["last_name_02"] = $parts[11];
            }
        }

        if (array_key_exists(12, $parts)) {
            if ($parts[12] == '+') {
                $results["first_name_03"] = $parts[13];
                $results["last_name_03"] = $parts[14];
            }
        }
        return $substitutes;
    }

    private function filterEvents($events)
    {
        if (isset($events["employee"]["absence_type"])) {
            return !in_array($events["employee"]["absence_type"], $this->wrongAbsenceTypes);
        }
        return false;
    }

    private function splitString($inputString)
    {
        // seperate the id from the 'urlaub'
        $parts = preg_split("/(,?\s+)|((?<=[a-z])(?=\d))|((?<=\d)(?=[a-z]))/i", $inputString);
        return intval($parts[1]);
    }
}
