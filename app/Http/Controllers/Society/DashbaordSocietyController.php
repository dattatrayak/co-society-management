<?php

namespace App\Http\Controllers\Society;

use App\Http\Controllers\Controller;
use App\Http\Repository\AccountRepository;
use Illuminate\Support\Facades\Auth;

class DashbaordSocietyController extends Controller
{
    private $userId = null;
    private $accountRepository = null;

    public function __construct(AccountRepository $accountRepository)
    {
        $societyUser = Auth::guard('society_user')->user();
        $this->userId = $societyUser->id;
        $this->accountRepository = $accountRepository;
    }
    public function dashboard()
    {
        $balance = $this->accountRepository->getBalances();
        return view('society.dashboard.index', compact('balance'));
    }
}
