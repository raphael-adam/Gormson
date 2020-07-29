<?php

namespace App\Console\Commands;

use App\Http\Controllers\LeaveController;
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

        // ToDo auslagern in Repo / Contract
        $leaveController = new LeaveController();
        $onLeave = $leaveController->onLeave();
        //$nextWeek = $leaveController->nextWeekVacation();
        $onVacationMessage = "Currently absent: "."\n";

       foreach($onLeave as $leave) {
           // add line break at the beginning of each leave
           $onVacationMessage .= "\n";

           // Employee on leave
           $onVacationMessage .= $leave->employee->first_name." ";
           $onVacationMessage .= $leave->employee->last_name." from: ";

           // Leave dates
           $onVacationMessage .= "*".$leave->vacation_begin."* until: ";
           $onVacationMessage .= "*".$leave->vacation_end."* ";

           // Substitute 01 info
           $onVacationMessage .= "\n"."If you have questions please refer to: ";
           $onVacationMessage .= $leave->substitute01->first_name." ";
           $onVacationMessage .= $leave->substitute01->last_name.", ";

           // Substitute 02 info
           $onVacationMessage .= $leave->substitute02->first_name." ";
           $onVacationMessage .= $leave->substitute02->last_name.", ";

           // Substitute 03 info
           $onVacationMessage .= $leave->substitute03->first_name." ";
           $onVacationMessage .= $leave->substitute03->last_name."";

           // add line break at the end of each leave
           $onVacationMessage .= "\n";

        }

       //echo $onVacationMessage;

        $respone = Http::withHeaders([
            'Content-Type' => 'application/json; charset=UTF-8',
        ])->post(env('WEBHOOK_URL'), [
            'text' => $onVacationMessage,
        ]);

        //print_r($respone);

        // need to check first if model is empty
        //print_r($onVacation[0]->vacation_begin); // getting the attributes from the collection
        //print_r($onVacation[0]->employee) getting the employee from the other table -> name based on relationship function name;
    }
}
