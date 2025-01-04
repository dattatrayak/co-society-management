<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'society_id',
        'name',
        'flat_count',
        'floor',
         'flat_no_start',
         'flat_per_floor',
         'society_flat_types_id',
         'cctv',
         'lift',
         'water_tank',
         'building_img',
         'floor_plan'
        ];

        public function flats()
        {
            return $this->hasMany(Flat::class, 'building_id');
        }
        public function societyMember()
        {
            return $this->hasMany(SocietyMember::class, 'building_id');
        }
        // public function society()
        // {
        //     return $this->belongsTo(SocietyUser::class, 'society_id');
        // }

        // public function flats()
        // {
        //     return $this->hasMany(Flat::class);
        // }
}
