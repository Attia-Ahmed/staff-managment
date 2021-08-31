<?php

namespace Tests\Unit;

use App\Models\EmployerSchedule;
use App\Repositories\AnalyticsService;
use App\Services\Employer\EmployerAnalytics;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use App\Models\Employer;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function var_dump;

class EmployerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_new_employer()
    {

        $employer = Employer::factory()->create();

        $this->assertDatabaseHas("employers", [
            "id" => $employer->id,
            "name" => $employer->name,
            "created_at" => $employer->created_at,
            "updated_at" => $employer->updated_at,
        ]);


    }

    public function test_status_relation()
    {
        /**
         * @var $employer Employer
         */
        $employer = Employer::factory()->create();
        $employerRepo = new EmployerAnalytics($employer);
        $employerRepo->updateStatus("offline", carbon::now()->subHours(9));
        $employerRepo->updateStatus("online", carbon::now()->subHours(8));
        $employerRepo->updateStatus("offline", carbon::now()->subHours(7));
        $employerRepo->updateStatus("online", carbon::now()->subHours(6));
        $this->assertEquals(2, $employer->last_status->id);
    }

    public function test_schdules_relation()
    {
        /**
         * @var $employer Employer
         */
        $employer = Employer::factory()->create();
        $day = carbon::today()->toDateString();
        $employer->schedules()->create([
            "day" => $day,
            "shift_start" => self::addHour($day, 1),
            "shift_end" => self::addHour($day, 2),
        ])->save();
        /**
         * @var $s EmployerSchedule
         */
        $s = $employer->schedules()->get()->last();
        $this->assertDatabaseHas("employer_schedules", [
            "id" => 1,
            "employer_id" => 1,
            "day" => $day
        ]);

    }

}
