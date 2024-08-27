<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyInformation extends Model
{
    use HasFactory;

    protected $fillable = ['company_name', 'address', 'phone_number', 'email', 'description'];

    public function contacts()
    {
        return $this->hasMany(ContactUs::class);
    }

    public function advertisements()
    {
        return $this->hasMany(Advertisement::class);
    }

    public function patrons()
    {
        return $this->hasMany(Patron::class);
    }
}
