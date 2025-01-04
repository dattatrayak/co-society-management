<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\SocietyFlatType;
use App\Models\SocietyMenu;
use App\Models\SocietyUserType;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\UserType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'datta@d.com',
            'password' => Hash::make('7u8i9o0p'),
            'role' => 'admin',
        ]);

        UserType::create(
            [
                'name' => 'Webmaster',
            ]
        );

        UserType::create(
            [
                'name' => 'admin',
            ]
        );
        UserType::create(
            [
                'name' => 'staff',
            ]
        );
        // add menu
        // $dashboard = Menu::create(['name' => 'Dashboard', 'url' => '/dashboard', 'order' => 1]);

        // Submenus for Users
        $menuManagemnt = Menu::create(
            [
                'name' => 'Dashboard',
                'url' => 'admin/dashboard',
                'icon' => 'fa-arrow-circle-right',
                'page_heading' => 'Society Managment admin Dashboard',
                'sub_heading' => null,
                'parent_id' => null,
                'order' => 1,
            ]
        );

        $menuManagemnt = Menu::create(
            [
                'name' => 'Menu Management',
                'url' => 'admin/menus',
                'icon' => 'fa-align-left',
                'page_heading' => 'Manage Menu',
                'sub_heading' => null,
                'parent_id' => null,
                'order' => 2,
            ]
        );

        $UserManagemnt = Menu::create(
            [
                'name' => 'User Management',
                'url' => 'admin/users',
                'icon' => 'fa-child',
                'page_heading' => 'User Management',
                'sub_heading' => null,
                'parent_id' => null,
                'order' => 3,
            ]
        );
        Menu::create(
            [
                'name' => 'User Type',
                'url' => 'admin/user-types',
                'icon' => 'fa-bookmark-o',
                'page_heading' => 'User Type management',
                'sub_heading' => null,
                'parent_id' => $UserManagemnt->id,
                'order' => 1,
            ]
        );
        Menu::create(
            [
                'name' => 'User Type Access',
                'url' => 'admin/user-types-permissions',
                'icon' => 'fa-cogs',
                'page_heading' => 'User Type Access Management',
                'sub_heading' => null,
                'parent_id' => $UserManagemnt->id,
                'order' => 1,
            ]
        );

        $societyUser = Menu::create(
            [
                'name' => 'Society Managment',
                'url' => 'admin/society-user',
                'icon' => 'fa-newspaper-o',
                'page_heading' => 'Society User Managment',
                'sub_heading' => null,
                'parent_id' => null,
                'order' => 4,
            ]
        );
        Menu::create(
            [
                'name' => 'Society user type',
                'url' => 'admin/society-user-types',
                'icon' => 'fa-newspaper-o',
                'page_heading' => 'Society User Type Managment',
                'sub_heading' => null,
                'parent_id' => $societyUser->id,
                'order' => 1,
            ]
        );
        Menu::create(
            [
                'name' => 'Society Menu',
                'url' => 'admin/society-menus',
                'icon' => 'fa-newspaper-o',
                'page_heading' => 'Society Menu Management',
                'sub_heading' => null,
                'parent_id' => null,
                'order' => 6,
            ]
        );
        

        SocietyUserType::create([
            'name' => 'Manager',
            'description' => 'Main Admin to manage all User access and reports access',
            'status' => 1,
        ]);
        SocietyUserType::create([
            'name' => 'Account',
            'description' => 'Account is a user to get access for all adding and general entries and manage the Money in the system',
            'status' => 1,
        ]);
        SocietyUserType::create([
            'name' => 'Security',
            'description' => 'Account is a user to get access for all adding and general entries and manage the Money in the system',
            'status' => 1,
        ]);

        SocietyUserType::create([
            'name' => 'Cleaning staff',
            'description' => 'Cleaning member ',
            'status' => 1,
        ]);

        SocietyUserType::create([
            'name' => 'Building',
            'description' => 'This is used to create building in the system',
            'status' => 1,
        ]);

        SocietyUserType::create([
            'name' => 'Flat',
            'description' => 'Adding flat to building',
            'status' => 1,
        ]);

        SocietyUserType::create([
            'name' => 'Parking',
            'description' => 'Adding parking to flat',
            'status' => 1,
        ]);

        $SocietyMenuDashboard = SocietyMenu::create(
            [
                'name' => 'Dashboard',
                'url' => 'society/dashboard',
                'icon' => 'fa-arrow-circle-right',
                'page_heading' => 'Society Managment admin Dashboard',
                'sub_heading' => null,
                'parent_id' => null,
                'order' => 1,
            ]
        );

        $societyUserManagemnt = SocietyMenu::create(
            [
                'name' => 'User Management',
                'url' => 'society/society-users',
                'icon' => 'fa-child',
                'page_heading' => 'User Management',
                'sub_heading' => null,
                'parent_id' => null,
                'order' => 3,
            ]
        );

        SocietyMenu::create([
            'name' => 'User Type Access',
            'url' => 'society/society-user-types-permissions',
            'icon' => 'fa-cogs',
            'page_heading' => 'User Type Access Management',
            'sub_heading' => null,
            'parent_id' => $societyUserManagemnt->id,
            'order' => 1,
        ]
        );
        $buildingMagement = SocietyMenu::create(
            [
                'name' => 'Building Manage',
                'url' => 'society/building',
                'icon' => 'fa-building-o',
                'page_heading' => 'Building Management',
                'sub_heading' => null,
                'parent_id' => null,
                'order' => 2,
            ]
        );
         SocietyMenu::create(
            [
                'name' => 'Flat Manage',
                'url' => 'society/flat',
                'icon' => 'fa-dedent',
                'page_heading' => 'Flat Management',
                'sub_heading' => null,
                'parent_id' => $buildingMagement->id,
                'order' => 1,
            ]
        );
        SocietyMenu::create(
            [
                'name' => 'Meter Manage',
                'url' => 'society/meter',
                'icon' => 'fa-dedent',
                'page_heading' => 'Meter Management',
                'sub_heading' => null,
                'parent_id' => $buildingMagement->id,
                'order' => 2,
            ]
        );
        SocietyMenu::create(
            [
                'name' => 'Member Management',
                'url' => 'society/member',
                'icon' => 'fa-hospital-o',
                'page_heading' => 'Society Member Managment',
                'sub_heading' => null,
                'parent_id' => null,
                'order' =>3,
            ]
        );
       
        
        SocietyFlatType::create([
            'name' => '1 RK',
            'description' => null,
            'status' => 1,
        ]);
        SocietyFlatType::create([
            'name' => '1 BHK',
            'description' => null,
            'status' => 1,
        ]);
        SocietyFlatType::create([
            'name' => '2 BHK',
            'description' => null,
            'status' => 1,
        ]);
        SocietyFlatType::create([
            'name' => '3 BHK',
            'description' => null,
            'status' => 1,
        ]);
        SocietyFlatType::create([
            'name' => '4 BHK',
            'description' => null,
            'status' => 1,
        ]);
    }
}
