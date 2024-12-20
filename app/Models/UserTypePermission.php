<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTypePermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'user_type_id',
        'view',
        'add',
        'delete',
        'view_own',
        'delete_own',
        'delete_other',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function userType()
    {
        return $this->belongsTo(UserType::class);
    }


}
