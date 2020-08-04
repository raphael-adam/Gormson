<?php


namespace App\Service;

use App\Contracts\MessageServiceContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class MessageService implements MessageServiceContract
{

    private $currentlyAbsent;
    private $nextWeek;
    private $absence;
    private $absentNow;
    private $nextAbsent;
    private $message;

    public function __construct()
    {
        $this->currentlyAbsent;
        $this->nextWeek;
    }

    /**
     * @return mixed
     */
    public function getCurrentlyAbsent()
    {
        return $this->currentlyAbsent;
    }

    /**
     * @param mixed $currentlyAbsent
     */
    public function setCurrentlyAbsent($currentlyAbsent): void
    {
        $this->currentlyAbsent = $currentlyAbsent;
    }

    /**
     * @return mixed
     */
    public function getNextWeek()
    {
        return $this->nextWeek;
    }

    /**
     * @param mixed $nextWeek
     */
    public function setNextWeek($nextWeek): void
    {
        $this->nextWeek = $nextWeek;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }

    private function construct()
    {
        $this->message = count($this->nextWeek) > 0 ? "*Absent in the next 7 days:* " . "\n" : '';
        $this->absence = $this->nextWeek;
        $this->messageBody();
        $this->absentNow = $this->message;

        $this->message = count($this->currentlyAbsent) > 0 ? "\n"."*Currently absent* " . "\n" : '';
        $this->absence = $this->currentlyAbsent;
        $this->messageBody();
        $this->nextAbsent = $this->message;

        $this->message = $this->nextAbsent."\n".$this->absentNow;
    }

    private function messageBody()
    {
        foreach ($this->absence as $absence) {
            // Employee on absence
            $this->message .= $absence->employee->first_name . " ";
            $this->message .= $absence->employee->last_name . " from: ";

            // absence dates
            $beginDate = Carbon::parse($absence->absence_begin)->format('M d, Y');
            $endDate = Carbon::parse($absence->absence_end)->format('M d, Y');
            $this->message .= "*$beginDate* until: $endDate ";
            if($absence['absence_type'] == 'Half a day') {
                $this->message .= "for half day";
            };

            if ($absence->substitute01 != Null) {
                $this->message .= "\n" . "If you have questions please refer to: ";
                $this->subInfo($absence->substitute01);
                if ($absence->substitute02 != Null) {
                    $this->subInfo($absence->substitute02);
                    if ($absence->substitute03 != Null) {
                        $this->subInfo($absence->substitute01);
                    }
                }
            }
            $this->message .= "\n";
        }
    }

    private function subInfo($substitute) {
        $this->message .= "$substitute->first_name " ;
        $this->message .= "$substitute->last_name. ";
    }

    public function send()
    {
        $this->construct();
        $response = Http::withHeaders([
            'Content-Type' => 'application/json; charset=UTF-8',
        ])->post(env('WEBHOOK_URL'), [
            'text' => $this->message
        ]);

    }


}
