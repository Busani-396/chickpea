<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 

class CampaignData extends Model
{
    use HasFactory; 
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
