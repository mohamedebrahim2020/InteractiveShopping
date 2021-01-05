<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature testLogin example.
     *
     * @return void
     */
    public function testSuccessful()
    {
        $user =  Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
        $this->withoutExceptionHandling();
        $response = $this->postJson('/api/login', ['email' => $user->email, 'password' => '123456789', 'device_name' => 'hima']);
        $response->assertJsonStructure([
            'status_code'  ,
            'message'  ,
            'user'  ,
            'token'  ,
        ]);
        $response->assertStatus(200);
        $response->assertOk();
    }

    public function testFailedAuthorization()
    {
        $user =  Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
        $this->withoutExceptionHandling();
        $response = $this->postJson('/api/login', ['email' => $user->email, 'password' => '123456', 'device_name' => 'hima']);
        $response->assertUnauthorized();
        $response->assertExactJson([
            'Message' => 'User not found. Please check your email or password',
        ]);
    }

    public function testFailedValidation()
    {
        $user =  Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
        //$this->withoutExceptionHandling();
        $response = $this->postJson('/api/login', ['email' => 'djdj', 'password' => '123', 'device_name' => 'hima'], ['Accept' => 'application/json']);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email','password']);
        $response->assertExactJson([
            "message" =>  "The given data was invalid.",
            "errors" => [
                "email" => [
                    "The email must be a valid email address."
                ],
                "password" => [
                    "The password must be at least 4 characters."
                ]
            ]
        ]);
    }
}
