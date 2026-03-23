<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\duplicateReport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<duplicateReport>
 */
class duplicateReportFactory extends Factory
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
            'attempted_user_id' => fake()->userName(),
            'ip_address' => fake()->ipv4(),
            'payload' => json_encode(['foo' => 'bar']),
        ];
    }
}
