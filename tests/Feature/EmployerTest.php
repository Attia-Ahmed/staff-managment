<?php

namespace Tests\Feature;

use Faker\Factory;
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
    use WithFaker;


    public function test_create_new_employer()
    {
        $this->withoutExceptionHandling();

        $name = $this->faker->name();

        $response = $this->post('api/employer', [
            "name" => $name
        ])
            ->assertStatus(201)
            ->assertJsonStructure([
                "id", "name", "created_at", "updated_at"
            ])->assertJson([
                "name" => $name
            ]);
        $responseData = json_decode($response->getContent());
        $this->assertDatabaseHas("employers", [
            "id" => (string)$responseData->id,
            "name" => $responseData->name,
            "created_at" => new Carbon($responseData->created_at),
            "updated_at" => new Carbon($responseData->updated_at)
        ]);
    }

}
