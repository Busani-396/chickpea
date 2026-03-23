<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 

class Campaign extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'name',
        'start_date',
        'end_date'
    ];

    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function data(){
        return $this->hasMany(CampaignData::class);
    }
}
