<?php

namespace Tests\Unit;

use App\Http\Controllers\EmployerStatusController;
use Tests\TestCase;
use App\Models\EmployerSchedule;
use App\Models\Employer;
use App\Models\EmployerStatus;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\EmployerAnalytics;

class EmployerAnalyticsTest extends TestCase
{
   use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_get_employer_analytics()
    {
        $employer=Employer::factory()->create();
        $c=new EmployerStatusController();
        $day=Carbon::create("2021-10-3 00:00:00");
        $request=new Request(["status"=>"online"]);
        $date=$day->addHours(2);
        $c->update($employer->id,$request,$date);
        $request=new Request(["status"=>"offline"]);
        $date=$day->addHours(3);
        $c->update($employer->id,$request,$date);
        $request=new Request(["status"=>"online"]);
        $date=$day->addHours(5);
        $c->update($employer->id,$request,$date);

        EmployerSchedule::create([
            "employer_id"=>$employer->id,
            "day"=>$day->format("Y-m-d"),
            "shift_start"=>$day->addHours(1)->format("h:m"),
            "shift_end"=>$day->addHours(4)->format("h:m")

        ]);
        //one hour

        EmployerSchedule::create([
            "employer_id"=>$employer->id,
            "day"=>$day->format("Y-m-d"),
            "shift_start"=>$day->addHours(5)->format("h:m"),
            "shift_end"=>$day->addHours(6)->format("h:m")

        ]); //one hour

       $e=new EmployerAnalytics();
       $day=Carbon::create("2021-10-3 00:00:00");
        $request=new Request(["day"=>$day->toString()]);
       $out=$e->show($employer->id,$request);
       var_dump($out);
     
        
    }
    
}
