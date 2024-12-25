<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patron extends Model
{
    use HasFactory;

    protected $fillable = ['company_information_id', 'patron_name', 'patron_email', 'patron_phone', 'patron_images', 'patron_description', 'twitter_link', 'fb_link', 'penterest_link', 'youtube_link', 'linkedin_link'];
    protected $casts = [
        'patron_images'=> 'array',
    ];

    public function company()
    {
        return $this->belongsTo(CompanyInformation::class);
    }
}
