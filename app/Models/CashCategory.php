<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashCategory extends Model
{
    protected $fillable = [
        'society_id',
        'name',
        'type',
        'description',
        'is_active',
        'created_by',
        'updated_BY',
    ];
}
