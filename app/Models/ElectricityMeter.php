<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ElectricityMeter extends Model
{
    use HasFactory;

    protected $fillable = [
        'society_id',
        'building_id',
        'electricity_meter'
    ];

    public function society()
    {
        return $this->belongsTo(SocietyUser::class);
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }
}
