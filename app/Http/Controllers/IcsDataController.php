<?php

namespace App\Http\Controllers;

use App\Leave\GetIcsData;
use Illuminate\Http\Request;

class IcsDataController extends Controller
{
    public function store(GetIcsData $getIcsData) {
        return $getIcsData->icsData();
    }
}
