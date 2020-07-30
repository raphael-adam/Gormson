<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    public function store($events)
    {
        return Employee::updateOrCreate(
        ['first_name' => $events["employee"]["first_name"], 'last_name' => $events["employee"]["last_name"]]
    );
    }
}
