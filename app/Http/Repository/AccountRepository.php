<?php


namespace App\Http\Repository;

use App\Models\Building;
use App\Models\CashTransaction;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountRepository
{
   private $userId = null;
   public function __construct()
   {
      $societyUser = Auth::guard('society_user')->user();
      $this->userId = $societyUser->id;
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
}
