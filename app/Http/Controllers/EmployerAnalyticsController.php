<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employer;


class EmployerAnalyticsController extends Controller
{

    public function show(Employer $employer, Request $request)
    {

        return response()->json([
            "total_working" => $employer->getDailyOnlineSeconds($request->day)
        ]);
    }


}
