<?php

namespace Database\Seeders;

use App\Models\SocietyUserType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class createSocietyUserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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

    }
}
