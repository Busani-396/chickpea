<?php

namespace App\Http\Controllers;

use App\Events\CampaignDataReceived;
use App\Models\Campaign;
use App\Models\CampaignData;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CampaignDataController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request, int $campaign_id)
   {
        try {
            if (!$request->expectsJson()){
                return $this->error('Please set Accept header to application/json', 406); 
            }

            $validated = $request->validate([
                '*.user_id'       => 'required|string',
                '*.video_url'     => 'required|string', 
                '*.custom_fields' => 'nullable|array'
            ]);

            $campaign = Campaign::find($campaign_id);

            if (!$campaign) {
                return $this->error('Campaign data not found', 404, 'Campaign data not found');
            }

            CampaignDataReceived::dispatch($validated, $campaign_id, $request->ip());
            
            return $this->success([], 'Data received and is being processed in the background', 202);

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Campaign Store Error: ' . $e->getMessage());
             return $this->error('Server error occurred', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CampaignData $campaignData)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CampaignData $campaignData)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CampaignData $campaignData)
    {
        //
    }
}
