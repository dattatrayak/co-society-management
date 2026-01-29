<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocietyFlatTypeMaintenance extends Model
{
    protected $fillable = [
        'society_id',
        'society_flat_type_id',
        'maintenance_amount',
        'status',
        'created_by',
        'updated_BY',
    ];
    public function society()
    {
        return $this->belongsTo(SocietyUser::class);
    }
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
