<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;
class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dashboard = Menu::create(['name' => 'Dashboard', 'url' => '/dashboard', 'order' => 1]);
        $users = Menu::create(['name' => 'Users', 'url' => '/users', 'order' => 2]);

        // Submenus for Users
        Menu::create(['name' => 'Add User', 'url' => '/users/add', 'parent_id' => $users->id, 'order' => 1]);
        Menu::create(['name' => 'List Users', 'url' => '/users/list', 'parent_id' => $users->id, 'order' => 2]);
    }
}
