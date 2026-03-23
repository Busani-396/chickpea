<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Campaign;
use App\Events\CampaignDataReceived;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;

class CampaignDataTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_successfully_receives_batch_data_and_dispatches_event()
    {
        Event::fake();
        $user = User::factory()->create();
        $campaign = Campaign::factory()->create();

        $payload = [
            [
                'user_id' => 'user_101',
                'video_url' => 'https://s3.com/video1.mp4',
                'custom_fields' => ['color' => 'red']
            ],
            [
                'user_id' => 'user_102',
                'video_url' => 'https://s3.com/video2.mp4'
            ]
        ];

        $response = $this->actingAs($user)
                         ->postJson("/api/campaigns/{$campaign->id}/data", $payload);

        $response->assertStatus(202)
                 ->assertJson([
                    'success' => true,
                    'status_code' => 202,
                    'message' => 'Data received and is being processed in the background',
                    'data' => []
                    ]);

        Event::assertDispatched(CampaignDataReceived::class, function ($event) use ($campaign, $payload) {
            return $event->campaignId === $campaign->id && count($event->batch) === 2;
        });
    }

    #[Test]
    public function it_returns_404_if_campaign_does_not_exist()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
                         ->postJson("/api/campaigns/9999/data", [
                             ['user_id' => '1', 'video_url' => 'url']
                         ]);

        $response->assertStatus(404)
                 ->assertJson([ 
                'status_code' => 404,
                'message' => 'Campaign data not found',
                'errors' => 'Campaign data not found'
                ]);
    }

    #[Test]
    public function it_fails_validation_if_payload_is_not_an_array_of_objects()
    {
        $user = User::factory()->create();
        $campaign = Campaign::factory()->create();

        $payload = [
            'user_id' => '1',
            'video_url' => 'url'
        ];

        $response = $this->actingAs($user)
                         ->postJson("/api/campaigns/{$campaign->id}/data", $payload);

        $response->assertStatus(422);
    }
}