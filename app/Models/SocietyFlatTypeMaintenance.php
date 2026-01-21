<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocietyFlatTypeMaintenance extends Model
{
    protected $fillable = [
        'society_flat_type_id',
        'maintenance_amount',
        'status',
    ];

    public function flatType()
    {
        return $this->belongsTo(
            SocietyFlatType::class,
            'society_flat_type_id'
        );
    }
    public function maintenance()
{
    return $this->hasOne(
        SocietyFlatTypeMaintenance::class,
        'society_flat_type_id'
    );
}
}
