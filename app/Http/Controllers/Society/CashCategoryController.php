<?php

namespace App\Http\Controllers\Society;

use App\Http\Controllers\Controller;
use App\Models\CashCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CashCategoryController extends Controller
{
    private $userId = null;

    public function __construct( )
    {
        $societyUser = Auth::guard('society_user')->user();
        $this->userId = $societyUser->id;
    }

    public function index()
    {
        $categories = CashCategory::latest()->paginate(10);
        return view('society.cash_categories.index', compact('categories'));
    }
 
    public function create()
    {
        return view('society.cash_categories.create');
    }

    /* =========================
       STORE
    ========================== */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
        ]);

        CashCategory::create([
            'society_id'  => $this->userId ?? null,
            'name'        => $request->name,
            'type'        => $request->type,
            'description' => $request->description,
            'is_active'   => $request->is_active ?? 1,
            'created_by'  => $this->userId ?? null,
        ]);

        return redirect()
            ->route('society.expencess-type.index')
            ->with('success', 'Cash category created successfully');
    }

    /* =========================
       EDIT VIEW
    ========================== */
    public function edit($id)
    {
        $category = CashCategory::findOrFail($id);
        return view('society.cash_categories.create', compact('category'));
    }

    /* =========================
       UPDATE
    ========================== */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
        ]);

        $category = CashCategory::findOrFail($id);

        $category->update([
            'name'        => $request->name,
            'type'        => $request->type,
            'description' => $request->description,
            'is_active'   => $request->is_active ?? 1,
        ]);

        return redirect()
            ->route('society.expencess-type.index')
            ->with('success', 'Cash category updated successfully');
    }

    /* =========================
       DELETE
    ========================== */
    public function destroy($id)
    {
        CashCategory::findOrFail($id)->delete();

        return redirect()
            ->route('society.expencess-type.index')
            ->with('success', 'Cash category deleted successfully');
    }
}