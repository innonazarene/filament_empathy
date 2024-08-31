<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patron extends Model
{
    use HasFactory;

    protected $fillable = ['company_information_id', 'patron_name', 'patron_email', 'patron_phone'];

    public function company()
    {
        return $this->belongsTo(CompanyInformation::class);
    }
}
