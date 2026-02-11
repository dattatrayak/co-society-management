<?php

namespace App\Http\Controllers\Society;

use App\Http\Controllers\Controller;
use App\Http\Repository\ExpencessRepository;
use App\Models\Expense;
use App\Models\CashTransaction;
use App\Models\CashCategory;
use App\Models\Flat;
use App\Models\SocietyMember;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ExpenseController extends Controller
{
    private $userId = null;

    public function __construct(private ExpencessRepository $expencessRepository)
    {
        $societyUser = Auth::guard('society_user')->user();
        $this->userId = $societyUser->id;
    }
    public function index(Request $request)
    {
        $expenses = Expense::with(['cashCategory', 'member'])
            ->when($request->year, fn($q) => $q->whereYear('expense_date', $request->year))
            ->when($request->month, fn($q) => $q->whereMonth('expense_date', $request->month))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderByDesc('expense_date')
            ->paginate(15);

        return view('society.expenses.index', compact('expenses'));
    }

    public function create()
    {
        $cashCategories = CashCategory::select('id', 'name')->where('type', 'expense')->get();
        $members = SocietyMember::all();
        $frequency = generateRecurringExpenses();
        $payment_mode = getPaymentModeArray();
        return view('society.expenses.create', compact(
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
            'expense_date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'payment_mode' => 'required',
            'paid_to_name' => 'nullable|string',
            'paid_to' => 'nullable|string',
            'check_no' => 'nullable|string',
            'attachment' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            //'reference_no' => 'nullable|string',
            'parent_expense_id' => 'nullable|integer|exists:expenses,id',
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
                    'transaction_date' => $data['expense_date'],
                    'transaction_type' => 'expense',
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
                'expense_date' => $data['expense_date'],
                'amount' => $data['amount'],
                'payment_mode' => $data['payment_mode'],
                'paid_to_name' => $data['paid_to_name'],
                'paid_to'  => $data['paid_to'],
                'check_no' => $data['check_no'] ?? null,
                'attachment' => $data['attachment'] ?? null,
                //'reference_no' => $data['reference_no'] ?? null,
                'parent_expense_id' => $data['parent_expense_id'] ?? null,
                'status' => $data['status'] ?? 'paid',
                'paid_on' => now(),
                'note' => $data['note'] ?? null,
                'created_by' => $this->userId,
            ]);

            DB::commit();
            return redirect()->route('society.expencess.index')
                ->with('success', 'Expense added successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $expense = Expense::withTrashed()->findOrFail($id);
        $parentExpense = null;

        if ($expense->parent_expense_id) {
            $parentExpense = Expense::find($expense->parent_expense_id);
        }
        //  dd($expense->parent_expense_id);
        $cashCategories = CashCategory::select('id', 'name')->where('type', 'expense')->get();
        $members = SocietyMember::all();
        $frequency = generateRecurringExpenses();
        $payment_mode = getPaymentModeArray();
        return view('society.expenses.create', compact(
            'expense',
            'cashCategories',
            'members',
            'frequency',
            'payment_mode',
            'parentExpense'

        ));
    }

    public function update(Request $request, $id)
    {
        $expense = Expense::withTrashed()->findOrFail($id);
        $data = $request->validate([
            'cash_category_id' => 'required|exists:cash_categories,id',
            'member_id' => 'nullable|exists:society_members,id',
            'frequency' => 'required',
            'expense_date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'payment_mode' => 'required',
            'paid_to_name' => 'nullable|string',
            'paid_to' => 'nullable|string',
            'check_no' => 'nullable|string',
            'parent_expense_id' => 'nullable|integer|exists:expenses,id',
            'attachment' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            //'reference_no' => 'nullable|string',
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
                if ($expense->attachment && Storage::disk('public')->exists('expencess_attachment/' . $expense->attachment)) {
                    Storage::disk('public')->delete('expencess_attachment/' . $expense->attachment);
                }

                $file = $request->file('attachment');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('expencess_attachment', $filename, 'public');

                $data['attachment'] = $filename;
            } else {
                // Keep old attachment
                $data['attachment'] = $expense->attachment;
            }

            /* ===============================
           Update cash transaction
        ================================*/
            if ($data['status'] == 'paid' && $expense->cash_transactions_id) {
                $expense->cashTransaction->update([
                    'cash_category_id' => $data['cash_category_id'],
                    'transaction_date' => $data['expense_date'],
                    'amount' => $data['amount'],
                    'payment_mode' => $data['payment_mode'],
                    //'reference_no' => $data['reference_no'],
                    'description' => $data['note'],
                ]);
            } else {
                $cashTransaction = CashTransaction::create([
                    'society_id' => $this->userId,
                    'cash_category_id' => $data['cash_category_id'],
                    'transaction_date' => $data['expense_date'],
                    'transaction_type' => 'expense',
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
            $expense->update([
                'cash_category_id' => $data['cash_category_id'],
                'society_members_id' => $data['member_id'] ?? null,
                'cash_transactions_id' => isset($cashTransaction) ? $cashTransaction->id : $expense->cash_transactions_id,
                'frequency' => $data['frequency'],
                'expense_date' => $data['expense_date'],
                'amount' => $data['amount'],
                'payment_mode' => $data['payment_mode'],
                'paid_to_name' => $data['paid_to_name'],
                'paid_to' => $data['paid_to'],
                'check_no' => $data['check_no'] ?? null,
                'attachment' => $data['attachment'],
                'parent_expense_id' => $data['parent_expense_id'] ?? null,
                //'reference_no' => $data['reference_no'] ?? null,
                'note' => $data['note'] ?? null,
                'status' => $data['status'] ?? 'paid',
            ]);

            DB::commit();

            return redirect()
                ->route('society.expencess.index')
                ->with('success', 'Expense updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
    public function searchParent(Request $request)
    {
        $expenses = $this->expencessRepository->getExpencessParent($request);

        return response()->json(
            $expenses->map(function ($e) {
                return [
                    'id' => $e->id,
                    'text' => "Ref#{$e->id} | {$e->paid_to_name} | ₹{$e->amount}"
                ];
            })
        );
    }
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return back()->with('success', 'Expense deleted');
    }
}
