<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoSessionLog extends Model
{
    use HasFactory;

    protected $table = 'video_session_logs';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'name',
        'remarks',
    ];
    public $timestamps = true;
    public $incrementing = true;

}


