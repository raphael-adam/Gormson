<?php

namespace App\Http\Controllers;

use App\Leave\ParseCalendar;

class VacationController extends Controller
{
    public function store(ParseCalendar $parseCalendar) {
        dd($parseCalendar->parseCalendar($parseCalendar));
    }
}
