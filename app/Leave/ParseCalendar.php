<?php


namespace App\Leave;


class ParseCalendar
{

    private $rawCalendar;
    private $wrongLeaveTypes;

    public function __construct($rawCalendar)
    {
        $this->rawCalendar = $rawCalendar;
        $this->wrongLeaveTypes = [
            "Homeoffice",
            "Feiertag",
            "Einheit",
            "Tag)",
            "-",
        ];
    }

    public function parseCalendar($rawData)
    {
        return "parsedData";
    }



}


/* return [
            "employee" => array(
                "firstnam" => "firstname",
                "lastname" => "lastname",
                "leavetype" => "Urlaub",
                "substitutes" => array(
                    "firtname01" => "firstname01",
                    "lastname01" => "lastname01",

                    "firtname02" => "firstname02",
                    "lastname02" => "lastname02",

                    "firtname03" => "firstname03",
                    "lastname03" => "lastname03",
                )
            ),
            "vacationID" => "uid",
            "leavestart" => "leaveStart",
            "leaveEnd" => "leaveEnd",
            "created" => "created",

        ];*/
