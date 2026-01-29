<?php

namespace Database\Seeders;

use App\Models\CashCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CashCategorySeeder extends Seeder
{
     public function run()
    {
        $categories = [
            ['name' => 'Maintenance Collection', 'type' => 'income','created_by'=>1],
            ['name' => 'Other Income', 'type' => 'income','created_by'=>1],
            ['name' => 'Late Fees', 'type' => 'income','created_by'=>1],
            ['name' => 'Daily Expenses', 'type' => 'expense','created_by'=>1],
            ['name' => 'Special Expenses', 'type' => 'expense','created_by'=>1],
            ['name' => 'Salary', 'type' => 'expense','created_by'=>1],
            ['name' => 'Electricity Bill', 'type' => 'expense','created_by'=>1],
            ['name' => 'Maintenance Expenses', 'type' => 'expense','created_by'=>1],
        ];

        foreach ($categories as $cat) {
            CashCategory::create($cat);
        }
    }
}
