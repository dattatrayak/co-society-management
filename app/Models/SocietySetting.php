<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocietySetting extends Model
{
    protected $fillable = [
        'society_id',
        'maintenance_late_fee',
        'late_fee_type',
        'grace_days',
        'created_by',
        'updated_BY',
    ];
}
