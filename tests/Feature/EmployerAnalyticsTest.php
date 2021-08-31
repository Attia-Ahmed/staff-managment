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
use App\Http\Controllers\EmployerAnalyticsController;
use App\Services\Employer\EmployerAnalytics;
use function var_dump;

class EmployerAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_analytics_route_for_fresh_employer_must_return_zero_working_seconds()
    {
        $employer = Employer::factory()->create();
        $day = carbon::today()->toDateString();
        $start_date = $day;
        $end_date = carbon::today()->addDay()->toDateString();
        $this->get("api/employer/{$employer->id}/analytics?start_date={$start_date}&end_date={$end_date}")
            ->assertStatus(200)
            ->assertJson([
                "total_working" => 0
            ]);
    }


    public function test_analytics_with_data()
    {

        $employer = Employer::factory()->create();
        $employerRepo = new EmployerAnalytics($employer);
        $day = carbon::today()->toDateString();
        $start_date = $day;
        $end_date = carbon::today()->addDay()->toDateString();

        //online form 2-4
        $employerRepo->updateStatus("online", self::addHour($day, 2));
        $employerRepo->updateStatus("offline", self::addHour($day, 4));
        //online form 6-8
        $employerRepo->updateStatus("online", self::addHour($day, 6));
        $employerRepo->updateStatus("offline", self::addHour($day, 8));
        //online form 10-12
        $employerRepo->updateStatus("online", self::addHour($day, 10));
        $employerRepo->updateStatus("offline", self::addHour($day, 12));
        //shift from 1-3 must overlap with 2-4 == 1hour
        EmployerSchedule::factory()->create([
            "employer_id" => $employer->id,
            "day" => $day,
            "shift_start" => self::addHour($day, 1),
            "shift_end" => self::addHour($day, 3)
        ]);
        //shift from 5-10 must overlap with 6-8 10-12 == 3hour
        EmployerSchedule::factory()->create([
            "employer_id" => $employer->id,
            "day" => $day,
            "shift_start" => self::addHour($day, 5),
            "shift_end" => self::addHour($day, 11)
        ]);
        $this->get("api/employer/{$employer->id}/analytics?start_date={$start_date}&end_date={$end_date}")
            ->assertStatus(200)
            ->assertJson(["total_working" => 4 * 60 * 60]);

    }

    function test_employer_analytics_if_employer_online()
    {

        $employer = Employer::factory()->create();
        $employerRepo = new EmployerAnalytics($employer);
        $day = carbon::today()->toDateString();
        $start_date = $day;
        $end_date = carbon::today()->addDay()->toDateString();

        // I am online since one hour
        $employerRepo->updateStatus("online", Carbon::now()->subHour(1));
        //shift before 2 hours and will coninue to 2 houres in future
        EmployerSchedule::factory()->create([
            "employer_id" => $employer->id,
            "day" => $day,
            "shift_start" => Carbon::now()->subHour(2),
            "shift_end" => Carbon::now()->addHours(2)
        ]);

        // so I'm working for must be 1 hour
        $this->get("api/employer/{$employer->id}/analytics?start_date={$start_date}&end_date={$end_date}")
            ->assertStatus(200)
            ->assertJson(["total_working" => 3600]);


    }

    function test_employer_analytics_if_employer_online_and_no_shift()
    {

        $employer = Employer::factory()->create();
        $employerRepo = new EmployerAnalytics($employer);
        $day = carbon::today()->toDateString();

        $start_date = $day;
        $end_date = carbon::today()->addDay()->toDateString();

        // I am online since 2 hour
        $employerRepo->updateStatus("online", Carbon::now()->subHour(2));
        //shift before 3 hours ended before 1 hour
        EmployerSchedule::factory()->create([
            "employer_id" => $employer->id,
            "day" => $day,
            "shift_start" => Carbon::now()->subHour(3),
            "shift_end" => Carbon::now()->subHour(1)
        ]);

        // so I'm working for must be 1 hour

        $this->get("api/employer/{$employer->id}/analytics?start_date={$start_date}&end_date={$end_date}")
            ->assertStatus(200)
            ->assertJson(["total_working" => 3600]);


    }

}
