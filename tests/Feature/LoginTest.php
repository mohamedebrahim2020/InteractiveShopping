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
    * @test
    */
    public function successful_login()
    {
        $user = User::factory()->create();
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
    /**
    * @test
    */
    public function failed_login_authorization_with_wrong_password()
    {
        $user = User::factory()->create();
        $response = $this->postJson('/api/login', ['email' => $user->email, 'password' => '123456', 'device_name' => 'hima']);
        $response->assertUnauthorized();
        $response->assertExactJson([
            'Message' => 'User not found. Please check your email or password',
        ]);
    }
    /**
    * @test
    */
    public function failed_login_validation_with_wrong_mail_password()
    {
        User::factory()->create();
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
