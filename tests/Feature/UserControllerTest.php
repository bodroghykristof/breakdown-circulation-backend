<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        // seed the database
        $this->artisan('db:seed');
        // alternatively you can call
        // $this->seed();
    }



    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIfUserAlreadyInDatabase()
    {
//        $user = User::factory()->count(3)->make();
        $response = $this->json('POST', '/api/register', [
            'name' => 'Rubie Gorczany',
            "email" => "tillman42@example.com",
            "password" => "password"]);

        $response
            ->assertStatus(409)
            ->assertExactJson([
                'message' => 'Email already used',
                'success' => false,
            ]);
    }

    public function testRegisterNewUser()
    {
        $response = $this->json('POST', '/api/register', [
            'name' => 'Rubie',
            "email" => "till@example.com",
            "password" => "passtill"]);

        $response->assertStatus(200);

//        $this->assertArrayHasKey("data", $response);
    }
}
