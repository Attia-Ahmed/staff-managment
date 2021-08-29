<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\EmployerSchedule;
use App\Models\Employer;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use function var_dump;

class EmployerScheduleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_add_employer_shift()
    {
        $day = Carbon::today()->toDateString();
        $employer = Employer::factory()->create();
        $shift = EmployerSchedule::create([
            "employer_id" => $employer->id,
            "day" => $day,
            "shift_start" => $shift_start = self::addHour($day, 1),
            "shift_end" => $shift_end = self::addHour($day, 3)
        ]);
        $this->assertDatabaseHas("employer_schedules", [
            "id" => $shift->id,
            "employer_id" => (string)$employer->id,
            "day" => $day,
            "shift_start" => $shift_start,
            "shift_end" => $shift_end
        ]);
    }
}
