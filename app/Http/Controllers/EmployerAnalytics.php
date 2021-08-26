<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employer;
use App\Models\EmployerSchedule;
use App\Models\EmployerStatus;
use Illuminate\Support\Carbon;
class EmployerAnalytics extends Controller
{
    //
    public function show(Employer $employer)
    {
        //
        
    }
    private function get_online_periods($day,Employer $employer){
        $periods=EmployerSchedule::where([
            "employer_id"=>$employer->id,
            
        ])->whereDay('offline_at', '<', $day)->whereDay('online_at', '>=', $day)->all();
        
            return $periods;
            
    }
    private function get_daily_working_hours($day,Employer $employer){
        $shifts=EmployerSchedule::where([
            "employer_id"=>$employer->id,
            
        ])->whereDay('day', '=', $day)->all();
            
        $online_periods=$this->get_online_periods($day, $employer);

        foreach($shifts as $shift){
            $shift_start=Carbon::create((String)$shift->day." ".(String)$shift->shift_start);
            $shift_end=Carbon::create((String)$shift->day." ".(String)$shift->shift_end);
            foreach($online_periods as $period){
                
                
                if($period->online_at >= $shift_start){
                    
                }

            }

        }

    }


}
