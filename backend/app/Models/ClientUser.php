<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientUser extends Model
{
    protected $table = 'client_users';

    protected $fillable = [
        'client_id',
        'user_id'
    ];
}
