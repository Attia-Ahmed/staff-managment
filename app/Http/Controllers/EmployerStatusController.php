<?php

namespace App\Http\Controllers;

use App\Models\EmployerStatus;
use Illuminate\Http\Request;
use App\Models\Employer;
use Illuminate\Support\Carbon;
use Symfony\Component\VarDumper\VarDumper;

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
    public function store($id,Request $request)
    {
        //
        
        
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
    public function update($id,Request $request)
    {
        
        if(!$request->date||config('app.env')!="testing"){
            $date=Carbon::now();
        }else{
            $date=$request->date;
        }
        //
        $employer=Employer::find($id);
        
        /*
        if($employer->status==$request->request("status")){
            //no need to change just infom client with current status

            return response()->json([
                "status"=>$employer->status
            ]);
        }*/
        
        $last_status=$employer->last_status();
        /**
         * we will change in these situation
         * 1-employer is online and no record found (create first record)
         * 3-employer is online and current status is offline (create new record)
         * 2-employer is offline and current status is online (modifie offline at record)
         * otherwise just infrom customer his status
         */
        $old_status=$employer->status;
        $new_status=$request->status;
       
        if(
            ($new_status=="online"&&$old_status=="offline")
            ){
            //this case is first time online;
            $last_status=EmployerStatus::create([
                "employer_id"=>$id,
                "online_at"=>$date
            ]);
            $employer->last_status_id=$last_status->id;
            $employer->save();


            }elseif($old_status=="online"&&$new_status=="offline"){


                $last_status->update([
                    "offline_at"=>$date
                ]);

            }
            
        return response()->json([
            "status"=>$employer->status
        ]);
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
