<?php


namespace App\Http\Repository;

use App\Models\CashCategory;

class CashCategoryRepository
{
    public function getExpencessCategoryDropdown()
    {
        return CashCategory::select('id', 'name')->where('type', 'expense')->where('is_active', '1')->get();
    }
    public function getIncomeCategoryDropdown()
    {
        return CashCategory::select('id', 'name')->where('type', 'income')->where('is_active', '1')->get();
    }
     public function getCategoryDropdown()
    {
        return CashCategory::select('id', 'name','type')->where('is_active', '1')->orderBy('TYPE', 'ASC')->get();
    }
}
