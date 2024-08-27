<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    use HasFactory;

    protected $fillable = ['company_information_id', 'contact_name', 'contact_email', 'contact_phone', 'message'];

    public function company()
    {
        return $this->belongsTo(CompanyInformation::class);
    }
}
