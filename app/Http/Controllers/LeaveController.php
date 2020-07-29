<?php

use App\Leave;

namespace App\Http\Controllers;

use App\Employee;
use App\Leave;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function updateLeave()
    {
        $employee = Employee::updateOrCreate(
            ['first_name' => 'Daniel'],
            ['last_name' => 'Marx']
        );

        $employee->leave()->updateOrCreate(['vacation_id' => 256], ['vacation_begin' => 20200707]);

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
