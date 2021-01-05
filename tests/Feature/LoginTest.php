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
    public function testLogin()
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
}
