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
        return response()->json([
            "total_woriking"=>$this->get_daily_working_seconds($day,Employer::find($id))
        ]);
    }
    private function get_online_periods($day,Employer $employer){
       // $day=Carbon::create($day)->format("y-m-d");
        $periods=EmployerStatus::where([
            "employer_id"=>$employer->id,
            
        ])->whereDate('offline_at', '<=', $day)->whereDate('online_at', '>=', $day)->get();
        $periods2=EmployerStatus::where([
            "employer_id"=>$employer->id,
            
        ])->whereNull("offline_at")->whereDate('online_at', '>=', $day)->get();
            $periods=$periods->toArray();
            $periods2=$periods2->toArray();
            $out=array_merge($periods,$periods2);
            return $out;
            
    }
    private function get_daily_working_seconds($day,Employer $employer){
        $shifts=EmployerSchedule::where([
            "employer_id"=>$employer->id,
            
        ])->whereDate('day', '=', $day)->get()->toArray();
        var_dump($shifts);
        $online_periods=$this->get_online_periods($day, $employer);
            $total_seconds=[];
        foreach($shifts as $shift){
            $shift_start=$shift["shift_start"];
            $shift_end=$shift["shift_end"];
            
            $peroid_real=0;
            foreach($online_periods as $period){
                if($period["online_at"] >$shift_end ){
                    break;
                }
                if($period["offline_at"] >$shift_start ){
                    var_dump($period);
                    break;
                }
                $real_start=$period["online_at"];
                
                if($period["online_at"] >= $shift_start){
                    $real_start=$shift_start;
                    
                }
                $real_end=$period["offline_at"];
                if( $period["offline_at"]== null||$period["offline_at"] <= $shift_end){
                    $real_end=$shift_end;
                }
                $peroid_real=Carbon::create($real_end)->diffInSeconds(Carbon::create($real_start));
               // var_dump($peroid_real);

            }
            $total_seconds[]=$peroid_real;
            

        }
        return array_sum($total_seconds);

    }


}
