<?php

namespace App\Http\Controllers;

use App\Events\CampaignDataReceived;
use App\Http\Requests\CampaignDataRequest;
use App\Jobs\CampaignDataJob;
use App\Models\Campaign;
use App\Models\CampaignData;
use App\Models\duplicateReport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
        // try{
        //     $request->validate([
        //         'user_id'       => 'required|int|exists:users,id|unique:campaign_data,user_id',
        //         'video_url'     => 'required|file|mimes:mp4,mov,ogg,qt|max:100000',//<
        //         'custom_fields' => 'nullable|array' 
        //     ]);

        //     $path = '';
        //     $campaign = Campaign::find($campaign_id);
        //     if (!$campaign){
        //         throw new Exception('Something is off here - campaign id not found !!');
        //     }

        //     if ($request->hasFile('video_url')){
        //         $path = 'myphoto/video_url';//$request->file('video_url')->store("campaigns/{$campaign_id}",'s3');
        //     }

        //     $data = CampaignData::create([
        //         'campaign_id'   => $campaign_id,
        //         'user_id'       => $request->user_id,
        //         'video_url'     => $path, 
        //         'custom_fields' => $request->custom_fields,
        //     ]);

        //     return response($data, 201);
        // }catch(ValidationException $e){
        //        $errors = $e->validator->errors();

        //     if ($errors->has('user_id') && str_contains($errors->first('user_id'), 'taken')) {
                    
        //         duplicateReport::create([
        //             'attempted_user_id' => $request->user_id,
        //             'campaign_id'       => $campaign_id,
        //             'ip_address'        => $request->ip(),
        //             'payload'           => $request->except('video_url'), 
        //         ]);

        //         \Log::warning('Duplicate entry attempt', [
        //             'value' => 'We have a problem*******',
        //             'ip' => 'anaother problem***********,'
        //         ]);
        //     }

        //     throw $e; 
        // }


        // 1. Validate the structure of the incoming array
    $validated = $request->validate([
        //'users'                 => 'required|array|min:1',
        
        '*.user_id'       => 'required|string',
        '*.video_url'     => 'required|string', // Use string since it's a URL now
        '*.custom_fields' => 'nullable|array'
    
    ]);


    //return ['hooooray'];

    $campaignExists = Campaign::where('id', $campaign_id)->exists();
    if (!$campaignExists) {
        return response()->json(['error' => 'Campaign not found'], 404);
    }

    // 2. Dispatch the Job to the background queue
    // We pass the whole array and the campaign ID
    //CampaignDataJob::dispatch($validated, $campaign_id, $request->ip());
    CampaignDataReceived::dispatch($validated, $campaign_id, $request->ip());
    //event(new CampaignDataReceived($validated, $campaign_id, $request->ip()));

    // 3. Return 202 Accepted immediately
    return response()->json([
        'message' => 'Data received and is being processed in the background.'
    ], 202);
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
