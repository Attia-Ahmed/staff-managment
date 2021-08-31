<?php

namespace App\Http\Controllers;
use App\Models\Employer;
use App\Services\Employer\EmployerAnalytics;
use Carbon\Carbon;
use Illuminate\Http\Request;


class EmployerController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Employer::create($request->all());

    }
    public function changeStatus(EmployerAnalytics $employer_analytics, Request $request)
    {

        $date = Carbon::now();

        $new_status = $request->status;
        $status = $employer_analytics->updateStatus($new_status, $date);


        return response()->json([
            "status" => $status
        ]);
    }

    public function employerAnalytics(EmployerAnalytics $employer_analytics, Request $request)
    {

        $start_date = Carbon::create($request->start_date);
        $end_date = Carbon::create($request->end_date);
        return response()->json([
            // todo move getDailyOnlineSeconds to Job (Queue) ???
            // todo search for the best practices feom the laravel ecosystem
            // todo redesign the getDailyOnlineSeconds method.

            "total_working" => $employer_analytics->getDailyOnlineSeconds($start_date, $end_date),
        ]);
    }
}
