<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Leave;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Ramsey\Collection\Collection;

class LeaveController extends Controller
{
    public function store($events)
    {
        $leave = Leave::updateOrCreate(
            ['vacation_id' => $events["vacationId"]], //ToDo check dated different
            ['employee_id' => $this->findEmployee($events),
                'substitute01_id' => $this->findSubstitute01($events["employee"]["substitutes"]),
                'substitute02_id' => $this->findSubstitute02($events["employee"]["substitutes"]),
                'substitute03_id' => $this->findSubstitute03($events["employee"]["substitutes"]),
                'vacation_begin' => $events["leaveStart"],
                'vacation_end' => $events["leaveEnd"],
                'leave_type' => $events["employee"]["leavetype"]]
        );
    }

    private function findEmployee($event)
    {
        $employee = Employee::where('last_name', $event["employee"]["lastname"])->get();
        return $employee[0]->id;
    }

    private function findSubstitute01($event)
    {
        if (array_key_exists('lastname01', $event)) {
            $employee = Employee::where('last_name', $event["lastname01"])->get();
            return $employee[0]->id;
        }
        return Null;
    }

    private function findSubstitute02($event)
    {
        if (array_key_exists('lastname02', $event)) {
            $employee = Employee::where('last_name', $event["lastname02"])->get();
            return $employee[0]->id;
        }
        return Null;
    }

    private function findSubstitute03($event)
    {
        if (array_key_exists('lastname03', $event)) {
            $employee = Employee::where('last_name', $event["lastname03"])->get();
            return $employee[0]->id;
        }
        return Null;
    }


    public function onLeave()
    {
        $today = Carbon::now();
        return Leave::where('vacation_begin', '<=', $today)
            ->where('vacation_end', '>=', $today)
            ->get();
    }

    public function onLeaveNextWeek()
    {
        $today = Carbon::now();
        $nextWeek = Carbon::now()->addWeek();

        return Leave::where('vacation_begin', '>=', $today)
            ->where('vacation_begin', '<=', $nextWeek)
            ->get();
    }
}
