<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignData extends Model
{
    protected $fillable = [
        'campaign_id',
        'user_id',
        'video_url',
        'custom_fields'
    ];

    protected $casts = [
        'custom_fields' => 'array',
    ];

    public function campaign(){
        return $this->belongsTo(Campaign::class);
    }
}
