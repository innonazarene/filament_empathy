<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements HasAvatar
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_url',
        'custom_fields',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'custom_fields' => 'json'
        ];
    }
    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url ? Storage::url($this->avatar_url) : null;
    }

    function getActiveUsersInLastMinutes(int $minutes)
    {
        return DB::table(config('session.table'))
            ->distinct()
            ->select(['users.id', 'users.name', 'users.email', 'users.avatar_url', 'users.custom_fields'])
            ->whereNotNull('user_id')
            ->where('sessions.last_activity', '>', Carbon::now()->subMinutes($minutes)->getTimestamp())
            ->leftJoin('users', config('session.table') . '.user_id', '=', 'users.id')
            ->groupBy('users.id', 'users.name', 'users.email', 'users.avatar_url')
            ->get();
    }
}
