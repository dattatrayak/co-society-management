<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SocietyFlatType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'status'];

    public function flats()
    {
        return $this->hasMany(Flat::class, 'society_flat_types_id');
    }
    
    protected static function booted()
    {
        static::saved(function ($model) {
            Cache::forget('society_flat_types');
            Cache::forget("society_{$model->society_id}_flat_types");
        });

        static::deleted(function ($model) {
            Cache::forget('society_flat_types');
            Cache::forget("society_{$model->society_id}_flat_types");
        });
    }
}
