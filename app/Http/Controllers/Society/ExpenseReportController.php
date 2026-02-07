<?php

namespace App\Http\Controllers\Society;

use App\Exports\ExpenseReportExport;
use App\Http\Controllers\Controller;
use App\Http\Repository\ExpencessRepository;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ExpenseReportController extends Controller
{
    private $userId = null;

    public function __construct(private ExpencessRepository $expencessRepository)
    {
        $societyUser = Auth::guard('society_user')->user();
        $this->userId = $societyUser->id;
    }
    public function export()
    {
        return Excel::download(new ExpenseReportExport, 'expenses_report.xlsx');
    }
}
