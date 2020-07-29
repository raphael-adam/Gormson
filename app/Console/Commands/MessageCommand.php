<?php

namespace App\Console\Commands;

use App\Contracts\IcsDataRepositoryContract;
use App\Contracts\ParseCalendarContract;
use App\Http\Controllers\LeaveController;
use App\Repository\IcsDataRepository;
use App\Repository\ParseCalendarRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class MessageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:MessageCommand';

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

       $calendarData = new IcsDataRepository();
       $rawData = $calendarData->get();

       $calendarParser = new ParseCalendarRepository();
       $calendarParser->setRawCalendar($rawData);
       $events = $calendarParser->parsedCalendar();

        $leaveController = new LeaveController();

        foreach ($events as $event) {
            $leaveController->updateLeave($event);
        }




        $onLeave = $leaveController->onLeave();
        $nextWeek = $leaveController->onLeaveNextWeek();


        // ToDo auslagern in Repo / Contract
        $AbsentMessage = count($nextWeek) > 0 ? "*Absent in the next 7 days:* " . "\n" : '';

        foreach ($nextWeek as $nextLeave) {
            // add line break at the beginning of each leave
            $AbsentMessage .= "\n";

            // Employee on leave
            $AbsentMessage .= $nextLeave->employee->first_name . " ";
            $AbsentMessage .= $nextLeave->employee->last_name . " from: ";

            // Leave dates
            $beginDate = Carbon::parse($nextLeave->vacation_begin)->format('M d, Y');
            $AbsentMessage .= "*" . $beginDate . "* until: ";
            $endDate = Carbon::parse($nextLeave->vacation_end)->format('M d, Y');
            $AbsentMessage .= "*" . $endDate . "* ";

            // Substitute 01 info
            if ($nextLeave->substitute01 != Null) {
                $AbsentMessage .= "\n" . "If you have questions please refer to: ";
                $AbsentMessage .= $nextLeave->substitute01->first_name . " ";
                $AbsentMessage .= $nextLeave->substitute01->last_name;
            }

            // Substitute 02 info
            if ($nextLeave->substitute02 != Null) {
                $AbsentMessage .= ", ";
                $AbsentMessage .= $nextLeave->substitute02->first_name . " ";
                $AbsentMessage .= $nextLeave->substitute02->last_name;
            }

            if ($nextLeave->substitute03 != Null) {
                $AbsentMessage .= ", ";
                $AbsentMessage .= $nextLeave->substitute03->first_name . " ";
                $AbsentMessage .= $nextLeave->substitute03->last_name . " ";
            }

            // add line break at the end of each leave
            $AbsentMessage .= "\n";
        }

        $AbsentMessage .= "\n";
        if (count($onLeave) > 0) {
            $AbsentMessage .= "*Currently absent:* " . "\n";
        }

        foreach ($onLeave as $leave) {
            // add line break at the beginning of each leave
            $AbsentMessage .= "\n";

            // Employee on leave
            $AbsentMessage .= $leave->employee->first_name . " ";
            $AbsentMessage .= $leave->employee->last_name . " from: ";

            // Leave dates
            $beginDate = Carbon::parse($leave->vacation_begin)->format('M d, Y');
            $AbsentMessage .= "*" . $beginDate . "* until: ";
            $endDate = Carbon::parse($leave->vacation_end)->format('M d, Y');
            $AbsentMessage .= "*" . $endDate . "* ";

            // Substitute 01 info
            if ($leave->substitute01 != Null) {
                $AbsentMessage .= "\n" . "If you have questions please refer to: ";
                $AbsentMessage .= $leave->substitute01->first_name . " ";
                $AbsentMessage .= $leave->substitute01->last_name;
            }

            // Substitute 02 info
            if ($leave->substitute02 != Null) {
                $AbsentMessage .= ", ";
                $AbsentMessage .= $leave->substitute02->first_name . " ";
                $AbsentMessage .= $leave->substitute02->last_name;
            }

            if ($leave->substitute03 != Null) {
                $AbsentMessage .= ", ";
                $AbsentMessage .= $leave->substitute03->first_name . " ";
                $AbsentMessage .= $leave->substitute03->last_name . " ";
            }

            // add line break at the end of each leave
            $AbsentMessage .= "\n";
        }

        $respone = Http::withHeaders([
            'Content-Type' => 'application/json; charset=UTF-8',
        ])->post(env('WEBHOOK_URL'), [
            'text' => $AbsentMessage,
        ]);
    }
}
