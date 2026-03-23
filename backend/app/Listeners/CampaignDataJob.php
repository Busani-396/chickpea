<?php

namespace App\Listeners;

use App\Events\CampaignDataReceived;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\CampaignData;
use App\Models\duplicateReport;

class CampaignDataJob implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CampaignDataReceived $event): void
    {
        foreach ($event->batch as $userData){
            $exists = CampaignData::where('campaign_id', $event->campaignId)
                ->where('user_id', $userData['user_id'])
                ->exists();

            if ($exists){
                duplicateReport::create([
                    'attempted_user_id' => $userData['user_id'],
                    'campaign_id' => $event->campaignId,
                    'ip_address'=> $event->ipAddress,
                    'payload'=> $userData,
                ]);
                continue;
            }

            CampaignData::create([
                'campaign_id'=> $event->campaignId,
                'user_id' => $userData['user_id'],
                'video_url'=> $userData['video_url'],
                'custom_fields'=> $userData['custom_fields'] ?? null,
            ]);
        }
    }
}
