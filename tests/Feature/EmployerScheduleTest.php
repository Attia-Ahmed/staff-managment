<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Employer;
use App\Models\EmployerSchedule;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Carbon;
class EmployerScheduleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    
    public function test_add_employer_schedule_route()
    {
        $this->withoutExceptionHandling();
        $employer=Employer::factory()->create();
        $response=$this->post("api/employer/{$employer->id}/schedule",[
            "employer_id"=>$employer->id,
            "day"=>$day=Carbon::now()->format("Y-m-d"),
            "shift_start"=>$shift_start=Carbon::now()->addHours(1),
            "shift_end"=>$shift_end=Carbon::now()->addHours(3)
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure(["id","employer_id","created_at","updated_at"]);
        $response->assertJson([
        "employer_id"=>$employer->id,
        "day"=>$day,
        "shift_start"=>$shift_start,
        "shift_end"=>$shift_end
    ]);
        $responseData=json_decode($response->getContent());
       $this->assertDatabaseHas("employer_schedules",[
        "id"=>$responseData->id,
        "employer_id"=>(String)$employer->id,
        "day"=>$day,
        "shift_start"=>$shift_start,
        "shift_end"=>$shift_end
    ]);
    }
}
