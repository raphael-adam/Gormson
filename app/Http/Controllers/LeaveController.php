<?php

use App\Leave;

namespace App\Http\Controllers;

use App\Employee;
use App\Leave;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Ramsey\Collection\Collection;

class LeaveController extends Controller
{
    public function updateLeave($events)
    {

        $employee = Employee::updateOrCreate(
            ['first_name' => $events["employee"]["firstname"]],
            ['last_name' => $events["employee"]["lastname"]]
        );

        $employee->leave()->updateOrCreate(
            ['vacation_id' => $events["vacationId"]],
            ['vacation_begin' => $events["leaveStart"],
                'vacation_end' => $events["leaveEnd"],
                'leave_type' => $events["employee"]["leavetype"]);
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
