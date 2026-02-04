<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain_id',
        'status_code',
        'response_time',
        'error_message'
    ];

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
}
