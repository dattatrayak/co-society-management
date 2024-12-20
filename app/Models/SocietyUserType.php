<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocietyUserType extends Model
{
    use HasFactory;

    protected $fillable = ['name','description','status'];


    public function societyUsers()
    {
        return $this->hasMany(SocietyUser::class, 'user_type_id');
    }
}
