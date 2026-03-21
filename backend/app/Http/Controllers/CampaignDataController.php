<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignData;
use Exception;
use Illuminate\Http\Request;

class CampaignDataController extends Controller
{
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
        $request->validate([
            'user_id'       => 'required|int|exists:users,id',
            'video_url'     => 'required|file|mimes:mp4,mov,ogg,qt|max:20000',//<- im limiting to only 20mb
            'custom_fields' => 'nullable|array' 
        ]);
        
        return response(['test'=>'Yes']);
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
