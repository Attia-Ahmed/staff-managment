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

class EmployerStatusTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_change_status_api()
    {
        $employer=Employer::factory()->create();
        $status="offline";
        $response = $this->post("api/employer/{$employer->id}/status",["status"=>$status]);
        
        $response->assertStatus(200);
        $response->assertJson(
            ["status"=>$status]
        );
        $this->assertDatabaseCount("employer_statuses",0);
        $status="offline";
        $response = $this->post("api/employer/{$employer->id}/status",["status"=>$status]);
        
        $response->assertStatus(200);
        $response->assertJson(
            ["status"=>$status]
        );
        $this->assertDatabaseCount("employer_statuses",0);
        $status="online";
        $response = $this->post("api/employer/{$employer->id}/status",["status"=>$status]);
        
        $response->assertStatus(200);
        $response->assertJson(
            ["status"=>$status]
        );
        $this->assertDatabaseCount("employer_statuses",1);
        $status="online";
        $response = $this->post("api/employer/{$employer->id}/status",["status"=>$status]);
        
        $response->assertStatus(200);
        $response->assertJson(
            ["status"=>$status]
        );
        $this->assertDatabaseCount("employer_statuses",1);
        $status="offline";
        $response = $this->post("api/employer/{$employer->id}/status",["status"=>$status]);
        
        $response->assertStatus(200);
        $response->assertJson(
            ["status"=>$status]
        );
        $this->assertDatabaseCount("employer_statuses",1);
        $status="online";
        $response = $this->post("api/employer/{$employer->id}/status",["status"=>$status]);
        
        $response->assertStatus(200);
        $response->assertJson(
            ["status"=>$status]
        );
        $this->assertDatabaseCount("employer_statuses",2);
        

    }
}
