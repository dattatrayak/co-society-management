<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocietyMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'society_id',
        'building_id',
        'name',
        'date_of_birth',
        'permanent_address',
        'pan',
        'uid',
        'email',
        'password',
        'mobile',
        'gender',
        'flat_id',
    ];

    // Relationships
    public function society()
    {
        return $this->belongsTo(SocietyUser::class);
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }
    public function flatType()
    {
        return $this->belongsTo(SocietyFlatType::class);
    }
    public function flats()
    {
        return $this->belongsTo(Flat::class);
    }
}
