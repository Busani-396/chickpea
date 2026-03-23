<?php
namespace Tests\Feature;

use App\Models\User;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CampaignTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function an_authenticated_user_can_create_a_campaign(){
        $user = User::factory()->create();
        $client = Client::factory()->create();

        $payload = [
            'client_id' => $client->id,
            'name' => 'BMW 2026',
            'start_date'=> '2026-04-01',
            'end_date'=> '2026-08-31',
        ];

        $response = $this->actingAs($user)
                         ->postJson('/api/campaigns', $payload);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Campaign created successfully', 
                 ])
                 ->assertJsonPath('data.name', 'BMW 2026'); 
        
        $this->assertDatabaseHas('campaigns', [
            'name'      => 'BMW 2026',
            'client_id' => $client->id
        ]);
    }

    #[Test]
    public function campaign_creation_fails_if_client_id_does_not_exist(){
        $user = User::factory()->create();

        $response = $this->actingAs($user)
                         ->postJson('/api/campaigns', [
                             'client_id'=> 99999, 
                             'name' => 'zero Campaign',
                             'start_date'=> '2026-05-01'
                         ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['client_id']);
    }

    #[Test]
    public function campaign_creation_fails_with_missing_required_fields(){
        $user = User::factory()->create();
        $response = $this->actingAs($user)
                         ->postJson('/api/campaigns', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['client_id', 'name', 'start_date']);
    }

    #[Test]
    public function guests_cannot_create_campaigns(){
        $response = $this->postJson('/api/campaigns', [
            'name' => 'Failure'
        ]);

        $response->assertStatus(401);
    }
}