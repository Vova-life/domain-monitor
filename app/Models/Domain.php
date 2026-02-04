<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'url',
        'check_interval',
        'timeout',
        'method',
        'last_checked_at'
    ];

    // Вказуємо, що last_checked_at — це дата (Carbon)
    protected $casts = [
        'last_checked_at' => 'datetime',
    ];

    // Зв'язок: один домен має багато логів
    public function checkLogs()
    {
        return $this->hasMany(CheckLog::class);
    }

    // Зв'язок: домен належить користувачу
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
