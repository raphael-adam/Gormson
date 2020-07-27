<?php
// Webhook
// https://chat.googleapis.com/v1/spaces/AAAA8rdNvns/messages?key=AIzaSyDdI0hCZtE6vySjMm-WEfRq3CPzqKqqsHI&token=K6f1SsBqrFXnC9httEJc4wYOPpg52S5Xqyux3BpdWSg%3D

namespace App\Console\Commands;

use App\Vacation;
use Carbon\Carbon;
use ICal\ICal;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use phpDocumentor\Reflection\Types\False_;
use phpDocumentor\Reflection\Types\Null_;

class VacationData extends Command
{
    // Leave types that should not show up in the final array
    private $filteredLeaveTypes = [
        "Homeoffice",
        "Einheit",
        "Feiertag",
        "-",
        "Tag)"
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:VacationData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // get the data
        $icalData = $this->getIcalData();
        // parse the data and get object back with all the events in the propper order
        $events = $this->parseData($icalData);
        $leave = $this->extractEvents($events);
        // fill the db with the data
        //$this->updateDB($events);
        // select current vacation
        //$onVacation = $this->selectOnVacation();
        // select vacation for next week
        //$nextVacation = $this->selectUpcomingVacations();
        // send message to chat if changed in last hour
        //this->sendMessages($onVacation, $nextVacation);


    }

    private function getIcalData()
    {
        return Http::get(env('TIMETAPE_API_URL'));
    }

    private function parseData($icalData)
    {
        $ical = new ICal($icalData, array(
            'defaultSpan' => 2,     // Default value
            'defaultTimeZone' => 'UTC',
            'defaultWeekStart' => 'MO',  // Default value
            'disableCharacterReplacement' => false, // Default value
            'filterDaysAfter' => null,  // Default value
            'filterDaysBefore' => null,  // Default value
            'skipRecurrence' => false, // Default value
        ));
        return $ical->events();
    }

    private function extractEvents($events)
    {
        $calendarEvents = [];
        foreach ($events as $event) {
            $summary = $event->summary;
            $calendarEvents[] = ["employee" => $this->extractEventDetails($summary),
                "uid" => $this->extractUid($event->uid),
                "leaveStart" => $event->dtstart,
                "leaveEnd" => $event->dtend,
                "created" => $event->created];
        }

        print_r(array_filter($calendarEvents, array($this, "filterEvents")));
        //return $this->filterEvents($calendarEvents);
    }

    private function filterEvents($events)
    {
        if (isset($events["employee"]["leavetype"])) {
            return !in_array($events["employee"]["leavetype"], $this->filteredLeaveTypes);
        }
        return false;
    }

    private function extractEventDetails($inputName)
    {
        $results = [];
        $parts = explode(' ', $inputName);

        if (array_key_exists(3, $parts)) {
            $results = ["firstname" => $parts[0],
                "lastname" => $parts[1],
                "leavetype" => $parts[3],
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
                $substitutes["firstname01"] = $parts[7];
                $substitutes["lasttname01"] = $parts[8];
            }
        }
        if (array_key_exists(9, $parts)) {
            if ($parts[9] == '+') {
                $results["firstname02"] = $parts[10];
                $results["lastname02"] = $parts[11];
            }
        }

        if (array_key_exists(12, $parts)) {
            if ($parts[12] == '+') {
                $results["firstname03"] = $parts[13];
                $results["lastname03"] = $parts[14];
            }
        }
        return $substitutes;
    }

    private function splitString($inputString)
    {
        // seperate the id from the 'urlaub'
        $parts = preg_split("/(,?\s+)|((?<=[a-z])(?=\d))|((?<=\d)(?=[a-z]))/i", $inputString);
        return intval($parts[1]);
    }

    private function matchEmployeeId($inputName)
    {
        switch ($inputName) {
            case 'Emmanouil Stafilarakis';
                1;
                break;
            case "Daniel Marx";
                2;
                break;
            case "Ilyes Tascou ";
                3;
                break;
            case "Jacqueline Wendel";
                4;
                break;
            case "Jens Konopka";
                5;
                break;
            case "Steven Metz";
                6;
                break;
            case 'Patrick Wieczorek';
                7;
                break;
            case 'Tim Gajewsky';
                8;
                break;
            case 'Oliver Böhm';
                9;
                break;
        }
    }
}


