<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flat extends Model
{
    use HasFactory;

    protected $fillable = [
        'society_id',
        'building_id',
        'flat_no',
        'society_flat_types_id',
        'floor_number',
        'maintance_per_month',
        'desc',
    ];
    // Relationship with Building
    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    // Relationship with FlatType
    public function flatType()
    {
        return $this->belongsTo(SocietyFlatType::class, 'society_flat_types_id');
    }
    public function society()
    {
        return $this->belongsTo(SocietyUser::class, 'society_id');
    }

    // public function building()
    // {
    //     return $this->belongsTo(Building::class,'building_id');
    // }

    // public function flatType()
    // {
    //     return $this->belongsTo(SocietyFlatType::class, 'society_flat_types_id');
    // }
}
