<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class SocietyUser extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'society_id',
        'name',
        'description',
        'address',
        'email',
        'email_verified_at',
        'password',
        'failed_attempts',
        'blocked_until',
        'user_type',
        'package_id',
        'package_start_date',
        'package_end_date',
        'title',
        'mobile_no',
        'building_count',
        'lift_count',
        'meter_count',
        'user_type_id',
        'society_image',
        'logo'
    ];

    protected $hidden = [
        'password',
    ];
     // Automatically hash the password
     public function setPasswordAttribute($value)
     {
         $this->attributes['password'] = Hash::make($value);
     }
// Relationship with UserType
    public function userType()
    {
        return $this->belongsTo(SocietyUserType::class, 'user_type_id');
    }
    // Parent menu relationship
    public function society()
    {
        return $this->belongsTo(SocietyUser::class, 'society_id');
    }

    public function buildings()
    {
        return $this->hasMany(Building::class);
    }

    public function flats()
    {
        return $this->hasMany(Flat::class);
    }

    public function meters()
    {
        return $this->hasMany(ElectricityMeter::class);
    }
    public function societyMember()
    {
        return $this->hasMany(SocietyMember::class);
    }
}
