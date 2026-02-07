<?php


namespace App\Http\Repository;

use App\Models\Building;
use App\Models\CashTransaction;
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
      $cashBalance = CashTransaction::where('society_id', $this->userId)
         ->where('payment_mode', 'cash')
         ->selectRaw("
            SUM(CASE WHEN transaction_type = 'income' THEN amount ELSE 0 END) -
            SUM(CASE WHEN transaction_type = 'expense' THEN amount ELSE 0 END)
            as balance
        ")
         ->value('balance');

      $bankBalance = CashTransaction::where('society_id', $this->userId)
       ->whereIn('payment_mode', ['bank', 'online','upi','cheque'])
         ->selectRaw("
            SUM(CASE WHEN transaction_type = 'income' THEN amount ELSE 0 END) -
            SUM(CASE WHEN transaction_type = 'expense' THEN amount ELSE 0 END)
            as balance
        ")
         ->value('balance');

      return [
         'cash' => $cashBalance ?? 0,
         'bank' => $bankBalance ?? 0,
      ];
   }
}
