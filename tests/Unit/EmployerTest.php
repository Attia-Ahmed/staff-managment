<?php

namespace Tests\Unit;
use Tests\TestCase;
use App\Models\Employer;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

        $employer=Employer::factory()->create();

        $this->assertDatabaseHas("employers",[
            "id"=>$employer->id,
            "name"=>$employer->name,
            "created_at"=>$employer->created_at,
            "updated_at"=>$employer->updated_at,
    ]);
       
    }
}
