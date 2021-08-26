<?php

namespace App\Http\Controllers;

use App\Models\EmployerSchedule;
use Illuminate\Http\Request;

class EmployerScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id,Request $request)
    {
        $data=$request->all();
        $data["employer_id"]=$id;
        return EmployerSchedule::create($data);
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployerSchedule  $employerSchedule
     * @return \Illuminate\Http\Response
     */
    public function show(EmployerSchedule $employerSchedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployerSchedule  $employerSchedule
     * @return \Illuminate\Http\Response
     */
    public function edit(EmployerSchedule $employerSchedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmployerSchedule  $employerSchedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmployerSchedule $employerSchedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployerSchedule  $employerSchedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmployerSchedule $employerSchedule)
    {
        //
    }
}
