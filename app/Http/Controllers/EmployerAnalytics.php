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
    public function show($id,Request $request)
    {
        //
        $day=$request->day;
        return response()->json([
            "total_working"=>$this->get_daily_working_seconds($day,Employer::find($id))
        ]);
    }
    private function get_online_periods($day,Employer $employer){
       $day=Carbon::create($day)->format("Y-m-d 00:00:00");
       $day=Carbon::create($day);//fix sqlite compitablity
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
        $online_periods=$this->get_online_periods($day, $employer);
            $total_seconds=[];
        foreach($shifts as $shift){ 
            $shift_start=$shift["shift_start"];
            $shift_end=$shift["shift_end"];

            $peroid_real=0;
            foreach($online_periods as $period){
                /**
                 * if user is online consider now is the online period
                 */
                if($period["offline_at"]== null){ //if user is online assume 

                    $period["offline_at"]=Carbon::now()->format("Y-m-d H:i:s");
                }
                   
                /**
                 * this case the user become online after this shift
                 * so we must scape
                 */
                if($period["online_at"] >$shift_end ){ 
                   
                    continue;
                }
                /**
                 * this case the user is  offline before this shift
                 * so we must scape
                 */
                if($period["offline_at"] <$shift_start ){
                    
                    continue;
                }


                /**
                 * this case the user is  online before  the this shift starts
                 * so we must calculate from shift start 
                 */
                $real_start=$period["online_at"];
                
                if($period["online_at"] <= $shift_start){
                    $real_start=$shift_start;
                    
                }

                /**
                 * this case the user is  offline after  the this shift end 
                 * so we must calculate unitl shift ends
                 */
                $real_end=$period["offline_at"];
                
                if($period["offline_at"] >= $shift_end){
                    $real_end=$shift_end;
                }
               
                $peroid_real=Carbon::create($real_start)->diffInSeconds(Carbon::create($real_end));
              
                array_push($total_seconds,$peroid_real);
            }
            
            
            
            

        }
        return array_sum($total_seconds);

    }


}
