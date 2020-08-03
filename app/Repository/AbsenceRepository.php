<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Repository\AbsenceRepositoryInterface;
use App\Absence;
use Carbon\Carbon;
use function GuzzleHttp\Promise\all;

class AbsenceRepository implements AbsenceRepositoryInterface
{

    protected $model;

    public function __construct(Absence $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function delete($id)
    {
        $this->model->getById()->delete($id);
    }

    public function create($absence)
    {
        Absence::updateOrCreate([
            'absence_id' => $absence["absence_id"],
            'absence_begin' => $absence["absence_begin"],
            'absence_end' => $absence["absence_end"]],
            ['employee_id' => $this->getByName($absence['employee']),
                'substitute_01_id' => $this->getByName($absence['employee']['substitutes'][0]),
                'substitute_02_id' => $this->getByName($absence['employee']['substitutes'][1]),
                'substitute_03_id' => $this->getByName($absence['employee']['substitutes'][2]),
                'absence_type' => $absence["employee"]["absence_type"]
            ]);
    }

    public function getByName($employee)
    {
        print_r($employee['first_name']);
        if ($employee['first_name'] == 'first_name') {
            return Null;
        }
       $absentee = Employee::where('last_name', $employee['last_name'])
            ->where('first_name', $employee['first_name'])
            ->first();
        return $absentee->id;

    }


    public function currentlyAbsent()
    {
        $today = Carbon::now();
        return Absence::where('absence_begin', '<=', $today)
            ->where('absence_end', '>=', $today)
            ->get();
    }

    public function absentNextWeek()
    {
        $today = Carbon::now();
        $nextWeek = Carbon::now()->addWeek();

        return Absence::where('absence_begin', '>=', $today)
            ->where('absence_begin', '<=', $nextWeek)
            ->get();
    }
}
