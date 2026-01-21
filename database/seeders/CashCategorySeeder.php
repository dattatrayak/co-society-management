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
            ['name' => 'Maintenance Collection', 'type' => 'income'],
            ['name' => 'Daily Expenses', 'type' => 'expense'],
            ['name' => 'Special Expenses', 'type' => 'expense'],
            ['name' => 'Salary', 'type' => 'expense'],
            ['name' => 'Electricity Bill', 'type' => 'expense'],
            ['name' => 'Maintenance Expenses', 'type' => 'expense'],
        ];

        foreach ($categories as $cat) {
            CashCategory::create($cat);
        }
    }
}
