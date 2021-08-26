<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Employer;
use Illuminate\Support\Carbon;
use Faker\Generator as Faker;
class EmployerTest extends TestCase
{
    use RefreshDatabase;

  

    public function test_create_new_employer(){
        $this->withoutExceptionHandling();
        $name="Ahmed";
        $response = $this->post('api/employer',["name"=>$name]);
        $response->assertStatus(201);
        $response->assertJsonStructure(["id","name","created_at","updated_at"]);
        $response->assertJson(["name"=>$name,]);
        $responseData=json_decode($response->getContent());
        $this->assertDatabaseHas("employers",[
            "id"=>(String)$responseData->id,
            "name"=>$responseData->name,
            "created_at"=>new Carbon($responseData->created_at),
            "updated_at"=>new Carbon($responseData->updated_at)
    ]);
    }

}
