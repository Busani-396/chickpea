<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;


class ClientTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    // public function test_example(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    use RefreshDatabase;

    // --- POSITIVE TESTS ---

    #[Test]
    public function an_authenticated_user_can_create_a_client()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
                         ->postJson('/api/client', [
                             'name' => 'Cocacola'
                         ]);

        $response->assertStatus(201)
                 ->assertJson(['message' => 'Client created successfully']);

        $this->assertDatabaseHas('clients', ['name' => 'Cocacola']);

        // $this->assertDatabaseHas('client_users', [
        //     'user_id' => $user->id,
        //     'client_id' => $response->json('client.id')
        // ]);

        $this->assertDatabaseHas('client_users', [
            'user_id' => $user->id,
            'client_id' => $response->json('data.id') 
        ]);
    }

    // --- NEGATIVE TESTS ---

    #[Test]
    public function a_guest_cannot_create_a_client()
    {
        $response = $this->postJson('/api/client', [
            'name' => 'BMW'
        ]);

        $response->assertStatus(401);
    }

    #[Test]
    public function client_creation_fails_without_a_name()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
                         ->postJson('/api/client', [
                             'name' => '' 
                         ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name']);
    }
}
