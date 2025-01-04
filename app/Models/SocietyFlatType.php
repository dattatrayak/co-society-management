<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocietyFlatType extends Model
{
    use HasFactory;

    protected $fillable = ['name','description','status'];

    public function flats()
    {
        return $this->hasMany(Flat::class, 'society_flat_types_id');
    }
    
}
