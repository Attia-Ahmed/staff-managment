<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use Illuminate\Http\Request;

class EmployerAnalyticsController extends Controller
{

    public function show(Employer $employer, Request $request)
    {
        return response()->json([
            // todo move getDailyOnlineSeconds to Job (Queue) ???
            // todo search for the best practices feom the laravel ecosystem
            // todo redesign the getDailyOnlineSeconds method.
            "total_working" => $employer->getDailyOnlineSeconds($request->day),
        ]);
    }

}
