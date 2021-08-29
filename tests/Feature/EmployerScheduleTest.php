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

    public function test_add_schedule_shifts()
    {
        $this->withoutExceptionHandling();

        $day = Carbon::today()
            ->toDateString();
        $shift_start = self::addHour($day, 1);
        $shift_end = self::addHour($day, 3);
        $employer = Employer::factory()->create();
        $response = $this->post("api/employer/{$employer->id}/schedule", [
            "employer_id" => $employer->id,
            "day" => $day,
            "shift_start" => $shift_start,
            "shift_end" => $shift_end
        ])
            ->assertStatus(201)
            ->assertJsonStructure(["id", "employer_id", "created_at", "updated_at"])
            ->assertJson([
                "employer_id" => $employer->id,
                "day" => $day,
                "shift_start" => $shift_start,
                "shift_end" => $shift_end
            ]);
        $responseData = json_decode($response->getContent());
        $this->assertDatabaseHas("employer_schedules", [
            "id" => $responseData->id,
            "employer_id" => (string)$employer->id,
            "day" => $day,
            "shift_start" => $shift_start,
            "shift_end" => $shift_end
        ]);
    }
}
