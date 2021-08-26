<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Employer;
use App\Models\EmployerStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
class EmployerStatusTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_add_status_event()
    {
        $employer=Employer::factory()->create();
        $status=EmployerStatus::create([
            "employer_id"=>$employer->id,
            "online_at"=>$online_at=Carbon::now(),
            
        ]);
        $this->assertDatabaseHas("employer_statuses",[
            "employer_id"=>(String)$employer->id,
            "online_at"=>$online_at,
            
        ]);
    }

    public function test_employer_last_seen()
    {

        

        $employer=Employer::factory()->create();

        $this->assertTrue((String)$employer->last_seen==(String)$employer->updated_at);
        
       // var_dump($employer->last_seen);
        $status=EmployerStatus::create([
            "employer_id"=>$employer->id,
            "online_at"=>$online_at=Carbon::now()->addHours(3)
        ]);
        $employer->update(["last_status_id"=>$status->id]);
        $employer->save();
        $employer=Employer::find($employer->id);
      
       $this->assertTrue((String)$employer->last_seen==(String)$employer->updated_at);

        $status->update([
            "offline_at"=>$offline_at=Carbon::now()->addHours(10)
        ]);
        $status->save();
        $employer=Employer::find($employer->id);
        $this->assertTrue((String)$employer->last_seen==(String)$offline_at);

        
        
       
    }
}
