<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocietyMember extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'society_id',
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
        'created_by',
        'updated_BY',
    ];

    // Relationships
    public function society()
    {
        return $this->belongsTo(SocietyUser::class);
    }
    public function flatType()
    {
        return $this->belongsTo(SocietyFlatType::class);
    }
    public function flats()
    {
        return $this->belongsToMany(Flat::class, 'member_flat', 'member_id', 'flat_id');
    }
}
