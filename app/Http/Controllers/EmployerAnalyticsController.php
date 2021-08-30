<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Repositories\EmployerRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployerAnalyticsController extends Controller
{


    public function show(Employer $employer, Request $request)
    {
        $employerRepo = new EmployerRepository($employer);
        $start_date = Carbon::create($request->start_date);
        $end_date = Carbon::create($request->end_date);
        return response()->json([
            // todo move getDailyOnlineSeconds to Job (Queue) ???
            // todo search for the best practices feom the laravel ecosystem
            // todo redesign the getDailyOnlineSeconds method.

            "total_working" => $employerRepo->getDailyOnlineSeconds($start_date, $end_date),
        ]);
    }

}
