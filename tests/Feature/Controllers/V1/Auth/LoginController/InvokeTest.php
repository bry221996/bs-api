<?php

namespace Tests\Feature\Controllers\V1\Auth\LoginController;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class InvokeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @group auth
     * @group auth.login
     */
    public function user_can_login_with_credentials()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json
                    ->has('token')
                    ->has('user.id')
                    ->where('user.id', $user->id)
                    ->has('user.name')
                    ->where('user.name', $user->name)
                    ->has('user.email')
                    ->where('user.email', $user->email)
                    ->has('user.created_at')
                    ->where('user.created_at', $user->created_at->toDateTimeString())
            );
    }
}
