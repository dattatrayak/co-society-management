<?php


namespace App\Http\Repository;

use App\Models\CashTransaction;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AccountRepository
{
   private $userId = null;
   public function __construct()
   {
      $societyUser = Auth::guard('society_user')->user();
      $this->userId = $societyUser->id;
   }
   public function baseQuery(Request $request)
   {
      return CashTransaction::with('category')
         ->where('society_id', $this->userId)
         ->when($request->search, function ($q) use ($request) {
            $q->where('amount', 'like', "%{$request->search}%")
               ->orWhere('description', 'like', "%{$request->search}%");
         })
         ->when($request->expense_from_date && $request->expense_to_date, function ($q) use ($request) {
            $q->whereBetween('transaction_date', [$request->expense_from_date, $request->expense_to_date]);
         })
         ->when($request->expense_from_date && !$request->expense_to_date, function ($q) use ($request) {
            $q->where('transaction_date', [$request->expense_from_date]);
         })
         ->when($request->year && !$request->month, function ($q) use ($request) {
            $q->whereBetween('transaction_date', [
               $request->year . '-01-01',
               $request->year . '-12-31'
            ]);
         })
         ->when($request->to_year && $request->from_month, function ($q) use ($request) {
            $startDate = $request->to_year . '-' . $request->from_month . '-01';
            $endDate = date('Y-m-t', strtotime($startDate));
            $q->whereBetween('transaction_date', [$startDate, $endDate]);
         })
         ->when($request->cash_category_id, function ($q) use ($request) {
            $q->where('cash_category_id', $request->cash_category_id);
         })
         ->when($request->payment_mode, function ($q) use ($request) {
            $q->where('payment_mode', $request->payment_mode);
         })
         ->orderBy('transaction_date', 'desc');
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
   public function getBalances()
   {
      $cashBalance = $this->getCashBalance('cash');

      $bankBalance = $this->getCashBalance('bank');
      $pendingIncomeCash = $this->getExpencessPainding('income', 'cash');
      $pendingIncomeBank = $this->getExpencessPainding('income', 'bank');
      $pendingExpenseCash = $this->getExpencessPainding('expense', 'cash');
      $pendingExpenseBank = $this->getExpencessPainding('expense', 'bank');

      return [
         'cash' => $cashBalance ?? 0,
         'bank' => $bankBalance ?? 0,
         'pending_income_cash' => $pendingIncomeCash,
         'pending_income_bank' => $pendingIncomeBank,
         'pending_expense_cash' => $pendingExpenseCash,
         'pending_expense_bank' => $pendingExpenseBank,
      ];
   }

   public function getCashBalance($type = 'bank')
   {

      $payment_mode = ($type == 'bank') ? ['bank', 'online', 'upi', 'cheque'] : ['cash'];
      return CashTransaction::where('society_id', $this->userId)
         ->whereIn('payment_mode', $payment_mode)
         ->selectRaw("
            SUM(CASE WHEN transaction_type = 'income' THEN amount ELSE 0 END) -
            SUM(CASE WHEN transaction_type = 'expense' THEN amount ELSE 0 END)
            as balance
        ")
         ->value('balance');
   }

   public function getExpencessPainding($transactionType = 'income', $type = 'bank')
   {
      $payment_mode = ($type == 'bank')
         ? ['bank', 'online', 'upi', 'cheque']
         : ['cash'];

      return Expense::where('society_id', $this->userId)
         ->whereIn('payment_mode', $payment_mode)
         ->where('status', 'pending')
         ->whereHas('cashCategory', function ($q) use ($transactionType) {
            $q->where('type', $transactionType);
         })
         ->selectRaw('COUNT(*) as total_records, SUM(amount) as total_amount')
         ->first();
   }

   /**
    * Get records for Excel export (NO pagination)
    */
   public function getForExport(Request $request)
   {
      return $this->baseQuery($request)->get();
   }
}
