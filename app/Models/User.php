<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'bio',
        'avatar',
        'balance',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'balance' => 'decimal:2',
        ];
    }

    // Role helpers
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isListener(): bool
    {
        return $this->role === 'listener';
    }

    public function isCaller(): bool
    {
        return $this->role === 'caller';
    }

    public function isOnline(): bool
    {
        return $this->status === 'online';
    }

    // Relationships
    public function callsAsCaller(): HasMany
    {
        return $this->hasMany(Call::class, 'caller_id');
    }

    public function callsAsListener(): HasMany
    {
        return $this->hasMany(Call::class, 'listener_id');
    }

    public function donationsGiven(): HasMany
    {
        return $this->hasMany(Donation::class, 'caller_id');
    }

    public function donationsReceived(): HasMany
    {
        return $this->hasMany(Donation::class, 'listener_id');
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        $initial = urlencode(substr($this->name, 0, 1));
        return "https://ui-avatars.com/api/?name={$initial}&background=7c3aed&color=fff&size=128";
    }
}
