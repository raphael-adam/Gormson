<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Absence;
use Carbon\Carbon;

class AbsenceController extends Controller
{
    public function store($events)
    {
        Absence::updateOrCreate([
            'absence_id' => $events["absence_id"],
            'absence_begin' => $events["absence_begin"],
            'absence_end' => $events["absence_end"]],
            ['employee_id' => $this->findEmployee($events["employee"]),
            'substitute_01_id' => $this->findSubstitute01($events["employee"]["substitutes"]),
            'substitute_02_id' => $this->findSubstitute02($events["employee"]["substitutes"]),
            'substitute_03_id' => $this->findSubstitute03($events["employee"]["substitutes"]),
            'absence_type' => $events["employee"]["absence_type"]
        ]);
    }

    // ToDo zu employee repo
    private function findEmployee($event)
    {
        $employee = Employee::where('last_name', $event["last_name"])
            ->where('first_name', $event["first_name"])
            ->firstOrFail();
        return $employee->id;
    }

    private function findSubstitute01($event)
    {
        if (array_key_exists('last_name_01', $event)) {
            $id = Employee::where('last_name', $event["last_name_01"])
                ->where('first_name', $event["first_name_01"])
                ->get();
            return $id[0]->id;
        }
        return Null;
    }

    private function findSubstitute02($event)
    {
        if (array_key_exists('last_name_02', $event)) {
            $id = Employee::where('last_name', $event["last_name_02"])
                ->where('first_name', $event["first_name_02"])
                ->get();
            return $id[0]->id;
        }
        return Null;
    }

    private function findSubstitute03($event)
    {
        if (array_key_exists('last_name_03', $event)) {
            $id = Employee::where('last_name', $event["last_name_03"])
                ->where('first_name', $event["first_name_03"])
                ->get();
            return $id[0]->id;
        }
        return Null;
    }


    public function onAbsence()
    {
        $today = Carbon::now();
        return Absence::where('absence_begin', '<=', $today)
            ->where('absence_end', '>=', $today)
            ->get();
    }

    public function onAbsenceNextWeek()
    {
        $today = Carbon::now();
        $nextWeek = Carbon::now()->addWeek();

        return Absence::where('absence_begin', '>=', $today)
            ->where('absence_begin', '<=', $nextWeek)
            ->get();
    }
}
