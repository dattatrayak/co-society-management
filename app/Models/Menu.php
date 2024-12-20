<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'url', 'icon', 'parent_id', 'page_heading', 'sub_heading', 'order'];

    // Parent menu relationship
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    // Children Menus
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order');
    }
    public function userTypePermissions()
    {
        return $this->hasMany(UserTypePermission::class);
    }
}
