<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\EmployerSchedule;
use Illuminate\Http\Request;

class EmployerScheduleController extends Controller
{

    public function store(Employer $employer, Request $request)
    {
        $data = $request->all();
        $data["employer_id"] = $employer->id;
        return EmployerSchedule::create($data);

    }


}
