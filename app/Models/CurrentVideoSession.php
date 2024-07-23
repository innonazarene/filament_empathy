<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentVideoSession extends Model
{
    use HasFactory;
    protected $table = 'current_video_sessions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'user_peer_connection',
        'name',
        'guest_peer_connection',
        'remarks',
    ];
    public $timestamps = true;
    public $incrementing = true;

}
