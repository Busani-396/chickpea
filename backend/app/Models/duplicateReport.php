<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class duplicateReport extends Model
{
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
