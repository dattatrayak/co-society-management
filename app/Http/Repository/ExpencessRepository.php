<?php


namespace App\Http\Repository;

use App\Models\Expense; 
use Illuminate\Http\Request;

class ExpencessRepository
{
    public function getExpencessParent(Request $request) {
        $term = $request->q;
       return Expense::whereNull('parent_expense_id') // only parents
        ->where(function ($q) use ($term) {
            $q->where('id', 'like', "%$term%")
              ->orWhere('paid_to_name', 'like', "%$term%") 
              ->orWhere('amount', 'like', "%$term%")
               ->orWhere('note', 'like', "%$term%");
        })
        ->limit(10)
        ->get();
    }
}
