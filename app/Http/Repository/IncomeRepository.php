<?php


namespace App\Http\Repository;

use App\Models\CashCategory;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeRepository
{

    private $userId = null;
    public function __construct()
    {
        $societyUser = Auth::guard('society_user')->user();
        $this->userId = $societyUser->id;
    }
    public function baseQuery(Request $request)
    {


        return  Expense::with(['cashCategory', 'member'])
            ->whereHas('cashCategory', function ($q) {
                $q->where('type', 'income');
            })
            ->where('society_id', $this->userId)
            ->when($request->search, function ($q) use ($request) {
                $q->where('amount', 'like', "%{$request->search}%")
                    ->orWhere('note', 'like', "%{$request->search}%");
            })
            ->when($request->expense_from_date && $request->expense_to_date, function ($q) use ($request) {
                $q->whereBetween('expense_date', [$request->expense_from_date, $request->expense_to_date]);
            })
            ->when($request->expense_from_date && !$request->expense_to_date, function ($q) use ($request) {
                $q->where('expense_date', [$request->expense_from_date]);
            })
            ->when($request->year && !$request->month, function ($q) use ($request) {
                $q->whereBetween('expense_date', [
                    $request->year . '-01-01',
                    $request->year . '-12-31'
                ]);
            })
            ->when($request->to_year && $request->from_month, function ($q) use ($request) {
                $startDate = $request->to_year . '-' . $request->from_month . '-01';
                $endDate = date('Y-m-t', strtotime($startDate));
                $q->whereBetween('expense_date', [$startDate, $endDate]);
            })
            ->when($request->cash_category_id, function ($q) use ($request) {
                $q->where('cash_category_id', $request->cash_category_id);
            })
            ->when($request->payment_mode, function ($q) use ($request) {
                $q->where('payment_mode', $request->payment_mode);
            })
            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            })->orderByDesc('expense_date');
    }
    /**
     * Get paginated records for listing
     */
    public function paginate(Request $request, int $perPage = 15)
    {
        return $this->baseQuery($request)
            ->paginate($perPage)
            ->withQueryString();
    }
}
