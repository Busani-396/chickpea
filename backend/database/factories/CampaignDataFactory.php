<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\CampaignData;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CampaignData>
 */
class CampaignDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       return [
            'campaign_id' => Campaign::factory(),
            'user_id' => fake()->unique()->userName(),
            'video_url' => fake()->url(),
            'custom_fields' => json_encode([
                'region' => fake()->state(),
                'platform' => 'mobile'
            ]),
        ];
    }
}
