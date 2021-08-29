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
    public function test_change_employer_status()
    {
        $this->withoutExceptionHandling();

        $employer = Employer::factory()->create();
        $status = "offline";
        $this->post("api/employer/{$employer->id}/status", ["status" => $status])
            ->assertStatus(200)
            ->assertJson(
                ["status" => $status]
            );
        $this->assertDatabaseCount("employer_statuses", 0);
        $status = "offline";
        $this->post("api/employer/{$employer->id}/status", ["status" => $status])->assertStatus(200)
            ->assertJson(
                ["status" => $status]
            );
        $this->assertDatabaseCount("employer_statuses", 0);
        $status = "online";
        $this->post("api/employer/{$employer->id}/status", ["status" => $status])
            ->assertStatus(200)
            ->assertJson(
                ["status" => $status]
            );
        $this->assertDatabaseCount("employer_statuses", 1);
        $status = "online";
        $this->post("api/employer/{$employer->id}/status", ["status" => $status])
            ->assertStatus(200)
            ->assertJson(
                ["status" => $status]
            );
        $this->assertDatabaseCount("employer_statuses", 1);
        $status = "offline";
        $this->post("api/employer/{$employer->id}/status", ["status" => $status])
            ->assertStatus(200)
            ->assertJson(
                ["status" => $status]
            );
        $this->assertDatabaseCount("employer_statuses", 1);
        $status = "online";
        $this->post("api/employer/{$employer->id}/status", ["status" => $status])
            ->assertStatus(200)
            ->assertJson(
                ["status" => $status]
            );
        $this->assertDatabaseCount("employer_statuses", 2);


    }
}
