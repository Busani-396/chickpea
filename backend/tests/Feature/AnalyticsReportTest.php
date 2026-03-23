<?php

namespace Tests\Feature\Console;

use App\Models\Campaign;
use App\Models\Client;
use App\Models\CampaignData;
use App\Models\duplicateReport as DuplicateReport; 
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AnalyticsReportTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_fails_gracefully_when_campaign_id_does_not_exist()
    {
        $this->artisan('app:analytics-report 9999')
             ->expectsOutput('Campaign with ID 9999 not found.')
             ->assertExitCode(0);
    }
}