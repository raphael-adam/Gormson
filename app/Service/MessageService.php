<?php


namespace App\Service;

use App\Contracts\MessageServiceContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;


class MessageService implements MessageServiceContract
{

    private $messageHeaders;
    private $message;
    private $currentlyAbsent;
    private $absentNextWeek;
    private $absentUpdate;
    private $absentMonday;

    /**
     * MessageService constructor.
     * @param $messageHeaders
     * @param $currentlyAbsent
     * @param $absentNextWeek
     * @param $absentUpdate
     * @param $absentMonday
     */
    public function __construct()
    {
        $this->messageHeaders;
        $this->message = '';
        $this->currentlyAbsent = [];
        $this->absentNextWeek = [];
        $this->absentUpdate = [];
        $this->absentMonday = [];


        $this->messageHeaders = [
            'currentlyAbsent' => '*Currently absent*' . "\n",
            'absentNextWeek' => "\n" . '*Absent in the next 7 days*' . "\n",
            'absentUpdate' => "\n" . '*Updated or new absence*' . "\n",
            'absentMonday' => "\n" . '*Will be absent on Monday*' . "\n",
        ];
    }

    public function send()
    {
        $this->constructMessage();
        Http::withHeaders([
            'Content-Type' => 'application/json; charset=UTF-8',
        ])->post(env('WEBHOOK_URL'), [
            'text' => $this->message
        ]);
    }

    private function constructMessage()
    {
        $header = $this->messageHeaders['currentlyAbsent'];
        $this->mapAbsence($this->currentlyAbsent, $header, false);

        $header = $this->messageHeaders['absentNextWeek'];
        $this->mapAbsence($this->absentNextWeek, $header, true);

        $header = $this->messageHeaders['absentUpdate'];
        $this->mapAbsence($this->absentUpdate, $header, true);

        $header = $this->messageHeaders['absentMonday'];
        $this->mapAbsence($this->absentMonday, $header, true);

    }

    private function mapAbsence($absence, $header, $toggle)
    {

        if ($this->isSet($absence) == true) {
            $this->message .= $header;
            foreach ($absence as $absent) {
                $this->messageBody($absent, $toggle);
            }
        }
    }

    private function messageBody($absence, $toggle)
    {
        $this->message .= $this->concatenateEmployee($absence->employee, $toggle);
        $this->message .= $this->constructDates($absence->absence_begin, $absence->absence_end, $toggle) . "\n";
        $this->message .= $this->substitutes($absence);
    }

    private function substitutes($substitutes)
    {
        $subs = '';
        if ($substitutes->substitute_01_id != Null) {
            $subs .= 'Please refer to: ';
            $subs .= $this->concatenateEmployee($substitutes->substitute01, false);
        }
        if ($substitutes->substitute_02_id != Null) {
            $subs .= ', ' . $this->concatenateEmployee($substitutes->substitute02, false);
        }
        if ($substitutes->substitute_03_id != Null) {
            $subs .= ', ' . $this->concatenateEmployee($substitutes->substitute03, false);
        }
        return $subs . "\n";
    }

    private function concatenateEmployee($employee, $isFrom = true)
    {
        $result = $isFrom == true ? ' from:' : '';
        return $employee['first_name'] . ' ' . $employee['last_name'] . $result;

    }

    private function constructDates($absence_begin, $absence_end, $isBegin)
    {
        $beginDate = $this->formatDates($absence_begin);
        $endDate = $this->formatDates($absence_end);
        return $this->concatenateDateString($beginDate, $endDate, $isBegin);
    }

    private function concatenateDateString($beginDate, $endDate, $isBegin)
    {
        $dateString = $isBegin == true ? " *" . $beginDate . '*' : '';
        return $dateString . " until: *" . $endDate . "* ";
    }

    private function formatDates($date)
    {
        return Carbon::parse($date)->format('M d D, Y');
    }

    private function isSet($absentType)
    {
        return count($absentType) > 0;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }

    /**
     * @param mixed $currentlyAbsent
     */
    public function setCurrentlyAbsent($currentlyAbsent): void
    {
        $this->currentlyAbsent = $currentlyAbsent;
    }

    /**
     * @param mixed $absentNextWeek
     */
    public function setAbsentNextWeek($absentNextWeek): void
    {
        $this->absentNextWeek = $absentNextWeek;
    }

    /**
     * @param mixed $absentUpdate
     */
    public function setAbsentUpdate($absentUpdate): void
    {
        $this->absentUpdate = $absentUpdate;
    }

    /**
     * @param mixed $absentMonday
     */
    public function setAbsentMonday($absentMonday): void
    {
        $this->absentMonday = $absentMonday;
    }

    /**
     * @param mixed $beginDateToggle
     */
    public function setBeginDateToggle($beginDateToggle): void
    {
        $this->beginDateToggle = $beginDateToggle;
    }


}
