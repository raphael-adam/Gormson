<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Repository\AbsenceRepositoryInterface;
use App\Absence;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class AbsenceRepository implements AbsenceRepositoryInterface
{

    protected $model;
    protected $employees;

    public function __construct(Absence $model)
    {
        $this->model = $model;
        $this->employees = [];
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function create($absence)
    {
        Absence::updateOrCreate([
            'absence_id' => $absence["absence_id"]],
            ['employee_id' => $this->getByName($absence['employee']),
                'substitute_01_id' => $this->getByName($absence['employee']['substitutes'][0]),
                'substitute_02_id' => $this->getByName($absence['employee']['substitutes'][1]),
                'substitute_03_id' => $this->getByName($absence['employee']['substitutes'][2]), 'absence_begin' => $absence["absence_begin"],
                'absence_end' => $absence["absence_end"],
                'absence_type' => $absence["employee"]["absence_type"],
            ]);
    }

    public function getByName($employee)
    {
        return Cache::rememberForever($employee['first_name'], function () use ($employee) {
            return Employee::where('first_name', $employee['first_name'])
                ->value('id');
        });
    }

    public function currentlyAbsent()
    {
        $today = Carbon::now();
        return Absence::where('absence_begin', '<=', $today)
            ->where('absence_end', '>=', $today)
            ->get();
    }

    public function absentInDayRange($start, $end)
    {
        $startDate = Carbon::now()->addDays($start);
        $EndDate = Carbon::now()->addDays($end);
        return Absence::where('absence_begin', '>=', $startDate)
            ->where('absence_begin', '<=', $EndDate)
            ->get();
    }

    public function absenceUpdated()
    {
        $lastHour = Carbon::now()->subHour();
        $today = Carbon::now();
        return Absence::where('absence_begin', '<=', $today)
            ->where('absence_end', '>=', $today)
            ->where('updated_at', '>', $lastHour)
            ->get();
    }

    public function deleteObsolete($events)
    {
        $absent = Absence::all();
        $databaseIds = $this->ids($absent);
        $eventIds = $this->ids($events);
        $differentIds = array_diff($databaseIds, $eventIds);
        Absence::whereIn('absence_id', $differentIds)->delete();
    }


    public function delete($id)
    {
        $this->model->getById()->delete($id);
    }

    private function ids($array)
    {
        $ids = [];
        foreach ($array as $item) {
            $ids[] = $item['absence_id'];
        }
        return $ids;
    }

}
