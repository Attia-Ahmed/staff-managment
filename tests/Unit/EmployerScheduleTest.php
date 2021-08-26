<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\EmployerSchedule;
use App\Models\Employer;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

class EmployerScheduleTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_add_employer_shift()
    {
        
        $employer=Employer::factory()->create();
        $shift=EmployerSchedule::create([
            "employer_id"=>$employer->id,
            "day"=>$day=Carbon::now()->format("Y-m-d"),
            "shift_start"=>$shift_start=Carbon::now()->addHours(1)->format("h:m"),
            "shift_end"=>$shift_end=Carbon::now()->addHours(3)->format("h:m")
        ]);
       $this->assertDatabaseHas("employer_schedules",[
        "id"=>$shift->id,
        "employer_id"=>(String)$employer->id,
        "day"=>$day,
        "shift_start"=>$shift_start,
        "shift_end"=>$shift_end
    ]);
    }
}
