<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoredResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'name',
        'protocol',
        'domain',
        'port',
        'ip',
        'is_active_ssl',
        'ssl_expired_at',
        'is_active',
        'last_status',
        'last_request_execution_time',
        'last_checked_at',
        'created_at',
        'updated_at'
    ];
}
