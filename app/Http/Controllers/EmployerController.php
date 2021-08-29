<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


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

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Employer $employer
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Employer $employer, Request $request)
    {

        if (!$request->date || config('app.env') != "testing") {
            $date = Carbon::now();
        } else {
            $date = $request->date;
        }
        $new_status = $request->status;
        $employer->updateStatus($new_status, $date);


        return response()->json([
            "status" => $employer->status
        ]);
    }
}
