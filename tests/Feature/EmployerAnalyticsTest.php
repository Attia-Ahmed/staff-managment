<?php

namespace Tests\Feature;

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
     * A basic feature test example.
     *
     * @return void
     */
    public function test_analytics_route_works()
    {
        $employer=Employer::factory()->create();
        $status="offline";
        $response = $this->get("api/employer/{$employer->id}/analytics?day=2021-08-26",);

        $response->assertStatus(200);
        $response->assertJson(["total_working"=>0]);
    }

    public static function add_hour($hour){
        $day="2021-08-26";
        return Carbon::create($day)->addHours($hour)->format("Y-m-d H:i:s");
        
    }
    public function test_analytics_with_data()
    {
        
        $employer=Employer::factory()->create();
        $day="2021-08-26";
     /*   $response = $this->get("api/employer/{$employer->id}/analytics?day={$day}");

        $response->assertStatus(200);

        $response->assertJson(["total_working"=>0]);
        */
        for( $i=1;$i<=6;++$i){
           
            $status= $i%2!=0 ? "online" : "offline";
           
        $response = $this->post("api/employer/{$employer->id}/status",["status"=>$status,"date"=>self::add_hour($i*2)]);
  
        }

        $response=$this->post("api/employer/{$employer->id}/schedule",[
            "employer_id"=>$employer->id,
            "day"=>$day,
            "shift_start"=>self::add_hour(1),
            "shift_end"=>self::add_hour(3)
        ]); //1-3 must overlap with 2-4 == 1hour
        $response=$this->post("api/employer/{$employer->id}/schedule",[
            "employer_id"=>$employer->id,
            "day"=>$day,
            "shift_start"=>self::add_hour(5),
            "shift_end"=>self::add_hour(11)
        ]); //5-11 must overlap with 6-8 10-12 == 3hour
        $response = $this->get("api/employer/{$employer->id}/analytics?day={$day}");

        $response->assertStatus(200);
        //ump($response->decodeResponseJson());
        $response->assertJson(["total_working"=>4*60*60]);

    }

    function test_employer_analytics_if_employer_online(){
        $employer=Employer::factory()->create();
        $day=Carbon::now()->format("Y-m-d");
        $response=$this->post("api/employer/{$employer->id}/schedule",[
            "employer_id"=>$employer->id,
            "day"=>$day,
            "shift_start"=>Carbon::now()->subHour(2)->format("Y-m-d H:i:s"),
            "shift_end"=>Carbon::now()->addHours(2)->format("Y-m-d H:i:s"),
        ]); //shift before 2 hours and will coninue to 2 houres in future
        $response = $this->post("api/employer/{$employer->id}/status",["status"=>"online",
        "date"=>Carbon::now()->subHour(1)->format("Y-m-d H:i:s")]);
        // I am online sence one hour
        // so resutl must be 1 hour
        $response = $this->get("api/employer/{$employer->id}/analytics?day={$day}");

        $response->assertStatus(200);
       
        $response->assertJson(["total_working"=>1*60*60]);


    }

    function test_employer_analytics_if_employer_online_and_no_shift(){
        $employer=Employer::factory()->create();
        $day=Carbon::now()->format("Y-m-d");
        $response=$this->post("api/employer/{$employer->id}/schedule",[
            "employer_id"=>$employer->id,
            "day"=>$day,
            "shift_start"=>Carbon::now()->subHour(3)->format("Y-m-d H:i:s"),
            "shift_end"=>Carbon::now()->subHour(1)->format("Y-m-d H:i:s"),
        ]); //shift before 3 hours ended before 1 hour
        $response = $this->post("api/employer/{$employer->id}/status",["status"=>"online",
        "date"=>Carbon::now()->subHour(2)->format("Y-m-d H:i:s")]);
        // I am online sence 2 hour
        // so resutl must be 1 hour
        $response = $this->get("api/employer/{$employer->id}/analytics?day={$day}");

        $response->assertStatus(200);
       
        $response->assertJson(["total_working"=>1*60*60]);


    }

}
