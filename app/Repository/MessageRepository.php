<?php


namespace App\Repository;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class MessageRepository
{

    private $onVacation;
    private $nextWeek;
    private $absence;
    private $currentlyAbsent;
    private $nextAbsent;
    private $message;
    private $response;

    /**
     * MessageRepository constructor.
     * @param $onVacation
     * @param $nextWeek
     * @param $message
     * @param $response
     */
    public function __construct($onVacation, $nextWeek)
    {
        $this->onVacation = $onVacation;
        $this->nextWeek = $nextWeek;
    }

    /**
     * @return mixed
     */
    public function getOnVacation()
    {
        return $this->onVacation;
    }

    /**
     * @param mixed $onVacation
     */
    public function setOnVacation($onVacation): void
    {
        $this->onVacation = $onVacation;
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
        $this->currentlyAbsent = $this->message;

        $this->message = count($this->onVacation) > 0 ? "\n"."*Currently absent* " . "\n" : '';
        $this->absence = $this->onVacation;
        $this->messageBody();
        $this->nextAbsent = $this->message;

        $this->message = $this->currentlyAbsent.$this->nextAbsent;
    }

    private function messageBody()
    {
        foreach ($this->absence as $absence) {
            // Employee on absence
            $this->message .= $absence->employee->first_name . " ";
            $this->message .= $absence->employee->last_name . " from: ";

            // absence dates
            $beginDate = Carbon::parse($absence->absence_begin)->format('M d, Y');
            $this->message .= "*" . $beginDate . "* until: ";
            $endDate = Carbon::parse($absence->absence_end)->format('M d, Y');
            $this->message .= "*" . $endDate . "* ";

            // Substitute 01 info
            if ($absence->substitute01 != Null) {
                $this->message .= "\n" . "If you have questions please refer to: ";
                $this->message .= $absence->substitute01->first_name . " ";
                $this->message .= $absence->substitute01->last_name;
            }

            // Substitute 02 info
            if ($absence->substitute02 != Null) {
                $this->message .= ", ";
                $this->message .= $absence->substitute02->first_name . " ";
                $this->message .= $absence->substitute02->last_name . ", ";;
            }

            if ($absence->substitute03 != Null) {
                $this->message .= ", ";
                $this->message .= $absence->substitute03->first_name . " ";
                $this->message .= $absence->substitute03->last_name . ", ";;
            }

            $this->message .= "\n";
        }
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
