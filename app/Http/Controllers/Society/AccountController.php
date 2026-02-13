<?php

namespace App\Http\Controllers\Society;

use App\Http\Controllers\Controller;
use App\Http\Repository\AccountRepository;
use App\Http\Repository\CashCategoryRepository;
use App\Models\ElectricityMeter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JournalExport;
class AccountController extends Controller
{
    private $userId = null;
    private $accountRepository = null;
    private $cashCategoryRepository = null;

    public function __construct(
        AccountRepository $accountRepository,
        CashCategoryRepository $cashCategoryRepository
    ) {
        $societyUser = Auth::guard('society_user')->user();
        $this->userId = $societyUser->id;
        $this->accountRepository = $accountRepository;
        $this->cashCategoryRepository = $cashCategoryRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $balance = $this->accountRepository->getBalances();
        $accountCategories = $this->cashCategoryRepository->getCategoryDropdown();
        $entries =  $this->accountRepository->paginate($request);
        $payment_mode = getPaymentModeArray();
        return view('society.account.index', compact('balance', 'entries', 'accountCategories', 'payment_mode', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //$buildings = $this->buildingRepository->getSocietyBuilding($this->userId);
        return view('society.meter.create', compact('buildings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ElectricityMeter $meter) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ElectricityMeter $meter) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ElectricityMeter $meter) {}

    public function download(Request $request)
    {
        return Excel::download(new JournalExport($request), 'journal.xlsx');
    }
}
