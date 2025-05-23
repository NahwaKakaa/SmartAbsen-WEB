<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    protected $fillable = [
        'user_type',
        'user_id',
        'aktivitas',
        'ip_address',
        'user_agent',
    ];
}

