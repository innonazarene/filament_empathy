<?php

namespace App\Models;

use App\Enums\RequestStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;
    protected $fillable = ['company_information_id', 'ad_title', 'ad_description', 'ad_image', 'status'];
    protected $casts = [
        'ad_image'=> 'array',
        'status'=> 'boolean',
    ];
    public function scopeHasActive(Builder $query)
    {
        $query->where('status', RequestStatus::ACTIVE->value);
    }
    public function company()
    {
        return $this->belongsTo(CompanyInformation::class);
    }
}
