<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\CampaignData;
use App\Models\duplicateReport;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CampaignDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected array $userDataBatch,
        protected int $campaignId,
        protected string $ipAddress
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->userDataBatch as $userData) {
            $userId = $userData['user_id'];

            $existingRecord = CampaignData::where('campaign_id', $this->campaignId)
                ->where('user_id', $userId)
                ->first();

             Log::info("EXISTING CODE PASSED " . __FILE__ . "ON LINE " . __LINE__);   

            if ($existingRecord) {
                duplicateReport::create([
                    'attempted_user_id' => $userId,
                    'campaign_id'       => $this->campaignId,
                    'ip_address'        => $this->ipAddress,
                    'payload'           => $userData,
                ]);

                Log::info("Duplicate detected for user {$userId} in campaign {$this->campaignId}. Skipping.");
                
                continue; 
            }

            CampaignData::create([
                'campaign_id'   => $this->campaignId,
                'user_id'       => $userId,
                'video_url'     => $userData['video_url'],
                'custom_fields' => $userData['custom_fields'] ?? null,
            ]);
        }
    }
}
