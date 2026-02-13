<?php

namespace App\Http\Controllers\Society;

use App\Http\Controllers\Controller;
use App\Http\Repository\CashCategoryRepository;
use App\Http\Repository\ExpencessRepository;
use App\Http\Repository\IncomeRepository;
use App\Models\Expense;
use App\Models\CashTransaction;
use App\Models\CashCategory;
use App\Models\Flat;
use App\Models\SocietyMember;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    private $userId = null;

    public function __construct(private ExpencessRepository $expencessRepository,
    private CashCategoryRepository $cashCategoryRepository,
    private IncomeRepository $incomeRepository )
    {
        $societyUser = Auth::guard('society_user')->user();
        $this->userId = $societyUser->id;
    }
    public function index(Request $request)
    {
        $request->validate([
            'expense_from_date' => 'nullable|date',
            'expense_to_date'   => 'nullable|date|after_or_equal:expense_from_date',
        ], [
            'expense_to_date.after_or_equal' => 'To date must be greater than or equal to From date.',
        ]);
        $payment_mode = getPaymentModeArray();
        $cashCategories = $this->cashCategoryRepository->getIncomeCategoryDropdown();
        $expenses = $this->incomeRepository->paginate($request);

        return view('society.income.index', compact('expenses','payment_mode','cashCategories','request'));
    }

    public function create()
    {
        $cashCategories = CashCategory::select('id', 'name')->where('type', 'income')->get();
        $members = SocietyMember::all();
        $frequency = generateRecurringExpenses();
        $payment_mode = getPaymentModeArray();
        return view('society.income.create', compact(
            'cashCategories',
            'members',
            'frequency',
            'payment_mode'
        ));
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'cash_category_id' => 'required|exists:cash_categories,id',
            'member_id' => 'nullable|exists:society_members,id',
            'frequency' => 'required',
            'income_date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'payment_mode' => 'required',
            'paid_to_name' => 'nullable|string',
            'paid_to' => 'nullable|string',
            'check_no' => 'nullable|string',
            'attachment' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            // 'reference_no' => 'nullable|string',
            'parent_income_id' => 'nullable|integer|exists:expenses,id',
            'note' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Upload attachment
            // Handle file upload
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('expencess_attachment', $filename, 'public');
                $data['attachment'] = $filename;
            }

            // Create cash transaction
            if ($data['status'] == 'paid') {
                $cashTransaction = CashTransaction::create([
                    'society_id' => $this->userId,
                    'cash_category_id' => $data['cash_category_id'],
                    'transaction_date' => $data['income_date'],
                    'transaction_type' => 'income',
                    'amount' => $data['amount'],
                    'payment_mode' => $data['payment_mode'],
                    //'reference_no' => $data['reference_no'],
                    'description' => $data['note'],
                    'created_by' => $this->userId,
                ]);
            }

            // Create expense
            Expense::create([
                'society_id' => $this->userId,
                'cash_category_id' => $data['cash_category_id'],
                'cash_transactions_id' => isset($cashTransaction) ? $cashTransaction->id : null,
                'society_members_id' => $data['member_id'] ?? null,
                'frequency' => $data['frequency'],
                'expense_date' => $data['income_date'],
                'amount' => $data['amount'],
                'payment_mode' => $data['payment_mode'],
                'paid_to_name' => $data['paid_to_name'],
                'paid_to'  => $data['paid_to'],
                'check_no' => $data['check_no'] ?? null,
                'attachment' => $data['attachment'] ?? null,
                //'reference_no' => $data['reference_no'] ?? null,
                'parent_expense_id' => $data['parent_income_id'] ?? null,
                'status' => $data['status'] ?? 'paid',
                'paid_on' => now(),
                'note' => $data['note'] ?? null,
                'created_by' => $this->userId,
            ]);

            DB::commit();
            return redirect()->route('society.income.index')
                ->with('success', 'Income added successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $income = Expense::withTrashed()->findOrFail($id);

        $parentIncome = null;

        if ($income->parent_expense_id) {
            $parentIncome = Expense::find($income->parent_expense_id);
        }
        $cashCategories = CashCategory::select('id', 'name')->where('type', 'income')->get();
        $members = SocietyMember::all();
        $frequency = generateRecurringExpenses();
        $payment_mode = getPaymentModeArray();
        return view('society.income.create', compact(
            'income',
            'cashCategories',
            'members',
            'frequency',
            'payment_mode',
            'parentIncome'

        ));
    }

    public function update(Request $request, $id)
    {
        $income = Expense::withTrashed()->findOrFail($id);
        $data = $request->validate([
            'cash_category_id' => 'required|exists:cash_categories,id',
            'member_id' => 'nullable|exists:society_members,id',
            'frequency' => 'required',
            'income_date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'payment_mode' => 'required',
            'paid_to_name' => 'nullable|string',
            'paid_to' => 'nullable|string',
            'check_no' => 'nullable|string',
            'parent_income_id' => 'nullable|integer|exists:expenses,id',
            'attachment' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            //  'reference_no' => 'nullable|string',
            'note' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {

            /* ===============================
           Handle attachment upload
        ================================*/
            if ($request->hasFile('attachment')) {

                // Delete old attachment if exists
                if ($income->attachment && Storage::disk('public')->exists('expencess_attachment/' . $income->attachment)) {
                    Storage::disk('public')->delete('expencess_attachment/' . $income->attachment);
                }

                $file = $request->file('attachment');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('expencess_attachment', $filename, 'public');

                $data['attachment'] = $filename;
            } else {
                // Keep old attachment
                $data['attachment'] = $income->attachment;
            }

            /* ===============================
           Update cash transaction
        ================================*/
            if ($data['status'] == 'paid' && $income->cash_transactions_id) {
                $income->cashTransaction->update([
                    'cash_category_id' => $data['cash_category_id'],
                    'transaction_date' => $data['income_date'],
                    'amount' => $data['amount'],
                    'payment_mode' => $data['payment_mode'],
                    'reference_no' => '',
                    'description' => $data['note'],
                ]);
            } else {
                $cashTransaction = CashTransaction::create([
                    'society_id' => $this->userId,
                    'cash_category_id' => $data['cash_category_id'],
                    'transaction_date' => $data['income_date'],
                    'transaction_type' => 'income',
                    'amount' => $data['amount'],
                    'payment_mode' => $data['payment_mode'],
                    //'reference_no' => $data['reference_no'],
                    'description' => $data['note'],
                    'created_by' => $this->userId,
                ]);
            }

            /* ===============================
           Update expense
        ================================*/
            $income->update([
                'cash_category_id' => $data['cash_category_id'],
                'society_members_id' => $data['member_id'] ?? null,
                'frequency' => $data['frequency'],
                'cash_transactions_id' => isset($cashTransaction) ? $cashTransaction->id : $income->cash_transactions_id,
                'expense_date' => $data['income_date'],
                'amount' => $data['amount'],
                'payment_mode' => $data['payment_mode'],
                'paid_to_name' => $data['paid_to_name'],
                'paid_to' => $data['paid_to'],
                'check_no' => $data['check_no'] ?? null,
                'attachment' => $data['attachment'],
                'parent_expense_id' => $data['parent_income_id'] ?? null,
                // 'reference_no' => $data['reference_no'] ?? null,
                'note' => $data['note'] ?? null,
                'status' => $data['status'] ?? 'paid',
            ]);

            DB::commit();

            return redirect()
                ->route('society.income.index')
                ->with('success', 'Income updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
    // public function searchParent(Request $request)
    // {
    //     $income = $this->expencessRepository->getExpencessParent($request);

    //     return response()->json(
    //         $income->map(function ($e) {
    //             return [
    //                 'id' => $e->id,
    //                 'text' => "Ref#{$e->id} | {$e->paid_to_name} | ₹{$e->amount}"
    //             ];
    //         })
    //     );
    // }
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return back()->with('success', 'Income deleted');
    }
}
