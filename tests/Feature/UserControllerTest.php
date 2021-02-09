<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        error_log("setup");

        // seed the database
        $this->artisan('db:seed', ['--class' => 'UserSeeder']);
        // alternatively you can call
        // $this->seed();
        error_log("afterseed");
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $user = User::factory()->count(3)->make();
        $response = $this->json('POST', '/api/register', ['name' => 'Sally', "email" => "tomi@tomi.com", "password" => "valami"]);

        $response
            ->assertStatus(409)
            ->assertExactJson([
                'message' => 'Email already used',
                'success' => false,
            ]);
    }

    public function test_example1()
    {
        $response = $this->json('POST', '/api/register', ['name' => 'Sally', "email" => "tomi@9tomi.com", "password" => "valami"]);

        $response
            ->assertStatus(200);

        $this->assertArrayHasKey("data", $response);
    }
}
