<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class AnalyticsReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_generates_report(){
        $campaign = \App\Models\Campaign::factory()->create();

        $this->artisan('app:analytics-report', ['id' => $campaign->id])
            ->expectsOutput("Generating Report for: {$campaign->name} (Client: {$campaign->client->name})")
            ->assertExitCode(0);
    }
}
