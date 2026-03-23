<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    // --- POSITIVE TESTS ---

    #[Test]
    public function a_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Busani',
            'email' => 'busani@gmail.com',
            'password' => '87654321',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Successfully registered'
                 ])
                 ->assertJsonStructure([
                     'data' => ['user', 'token']
                 ]);

        $this->assertDatabaseHas('users', ['email' => 'busani@gmail.com']);
    }

    #[Test]
    public function a_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'busani@gmail.com',
            'password' => bcrypt('87654321'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'busani@gmail.com',
            'password' => '87654321',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Successfully logged in'
                 ])
                 ->assertJsonStructure([
                     'data' => ['token']
                 ]);
    }

    #[Test]
    public function a_user_can_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('email')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/logout');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Successfully logged out'
                 ]);

        $this->assertCount(0, $user->tokens);
    }

    // --- NEGATIVE TESTS ---

    #[Test]
    public function it_fails_login_with_wrong_credentials()
    {
        $user = User::factory()->create(['password' => bcrypt('87654321')]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrong-pass',
        ]);

        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Invalid credentials'
                 ]);
    }

    #[Test]
    public function registration_fails_invalid_email()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Busani',
            'email' => 'not-an-email',
            'password' => '87654321',
        ]);

        
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    #[Test]
    public function registration_fails_duplicate_email_found()
    {
        User::factory()->create(['email' => 'busaniM@gmail.com']);

        $response = $this->postJson('/api/register', [
            'name' => 'Busani',
            'email' => 'busaniM@gmail.com',
            'password' => '11167865',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    #[Test]
    public function logout_fails_if_there_is_no_token()
    {
        $response = $this->postJson('/api/logout');
        $response->assertStatus(401);
    }
}