<?php

namespace App\Http\Controllers;

use App\Models\EmployerStatus;
use Illuminate\Http\Request;

class EmployerStatusController extends Controller
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
    public function store(Request $request)
    {
        //
        $status=EmployerStatus::create($request->all());
        return $status;
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployerStatus  $employerStatus
     * @return \Illuminate\Http\Response
     */
    public function show(EmployerStatus $employerStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployerStatus  $employerStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(EmployerStatus $employerStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmployerStatus  $employerStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmployerStatus $employerStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployerStatus  $employerStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmployerStatus $employerStatus)
    {
        //
    }
}
