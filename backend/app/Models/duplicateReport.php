<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class duplicateReport extends Model
{
    use HasFactory; 

    protected $fillable = [
        'attempted_user_id',
        'campaign_id',
        'ip_address',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
