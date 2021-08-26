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
    public function show($id,$request)
    {
        //
        $day=$request->day;
        response()->json([
            "total_woriking"=>$this->get_daily_working_seconds($day,Employer::find($id))
        ]);
    }
    private function get_online_periods($day,Employer $employer){
       // var_dump($day);
        $day=Carbon::create($day)->format("y-m-d");
        $periods=EmployerStatus::where([
            "employer_id"=>$employer->id,
            
        ])->whereDay('offline_at', '<', $day)->whereDay('online_at', '>=', $day)->get();
           // var_dump($periods);
            return $periods;
            
    }
    private function get_daily_working_seconds($day,Employer $employer){
        $shifts=EmployerSchedule::where([
            "employer_id"=>$employer->id,
            
        ])->whereDay('day', '=', $day)->get();
            
        $online_periods=$this->get_online_periods($day, $employer);
            $total_seconds=[];
        foreach($shifts as $shift){
            $shift_start=Carbon::create((String)$shift->day." ".(String)$shift->shift_start);
            $shift_end=Carbon::create((String)$shift->day." ".(String)$shift->shift_end);
            $peroid_real=0;
            foreach($online_periods as $period){
                if($period->online_at >$shift_end ){
                    break;
                }
                $real_start=$period->online_at;
                
                if($period->online_at >= $shift_start){
                    $real_start=$shift_start;
                    
                }
                $real_end=$period->offline_at;
                if( $period->offline_at== null||$period->offline_at <= $shift_end){
                    $real_end=$shift_end;
                }
                $peroid_real=$real_end->diffInSeconds($real_end);


            }
            $total_seconds[]=$peroid_real;
            

        }
        return array_sum($total_seconds);

    }


}
