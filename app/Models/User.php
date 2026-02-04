<?php

namespace App\Models;

// ... (інші імпорти можуть бути тут)
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany; // Додай цей рядок зверху

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * 👇 ДОДАЙ ЦЕЙ МЕТОД 👇
     * Зв'язок: один користувач має багато доменів
     */
    public function domains(): HasMany
    {
        return $this->hasMany(Domain::class);
    }
}
