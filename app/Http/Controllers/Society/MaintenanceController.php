<?php

namespace App\Http\Controllers\Society;

use App\Http\Controllers\Controller;
use App\Http\Repository\BuildingRepository;
use App\Http\Repository\FlatRepository;
use App\Http\Repository\MeterRepository;
use App\Models\Building;
use App\Models\CashTransaction;
use App\Models\ElectricityMeter;
use App\Models\Flat;
use App\Models\MaintenanceRecord;
use App\Models\SocietyFlatType;
use App\Models\SocietyFlatTypeMaintenance;
use App\Services\MaintenanceCalculatorService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MaintenanceController extends Controller
{
    private $userId = null;
    private $meterRepository = null;
    private $buildingRepository = null;
    private $flatRepository = null;

    public function __construct(
        MeterRepository $meterRepository,
        BuildingRepository $buildingRepository,
        private MaintenanceCalculatorService $maintenanceCalculatorService,
        FlatRepository $flatRepository
    ) {
        $societyUser = Auth::guard('society_user')->user();
        $this->userId = $societyUser->id;
        $this->meterRepository = $meterRepository;
        $this->buildingRepository = $buildingRepository;
        $this->flatRepository = $flatRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $buildings = Building::where('society_id', $this->userId)->select('name', 'id')->get();
        $societyFlatType = $this->flatRepository->getFlatTypeDropdown();
        $search = $request->input('search', '');
        $building_id = $request->input('building_id', null);
        $flat_type = $request->input('flat_type', null);

        $records = MaintenanceRecord::with(['flat.flatType', 'building'])
            ->when($request->year, function ($q) use ($request) {
                $q->where('year', $request->year);
            })
            ->when($request->month, function ($q) use ($request) {
                $q->where('month', $request->month);
            })
            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($search, function ($q) use ($search) {
                $q->whereHas('flat', function ($q) use ($search) {
                    $q->where('flat_no', 'LIKE', "%{$search}%");
                });
            })
            ->when($building_id, function ($q) use ($building_id) {
                $q->where('building_id', $building_id);
            })
            ->when($flat_type, function ($q) use ($flat_type) {
                $q->whereHas('flat', function ($q) use ($flat_type) {
                    $q->where('society_flat_type_id', $flat_type);
                });
            })
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->paginate(15);

        return view('society.maintenance.index', compact('records', 'buildings', 'societyFlatType'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $buildings = Building::where('society_id', $this->userId)->select('name', 'id')->get();
        $flats = Flat::where('society_id',  $this->userId)->get();
        return view('society.maintenance.create', compact('buildings', 'flats'));
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'flat_id'      => 'required|exists:flats,id',
            'year'         => 'required|digits:4',
            'month'        => 'required|integer|min:1|max:12',
            'amount'       => 'required|numeric',
            'payment_mode' => 'required|in:online,cash,cheque,dd',
            'check_no'     => 'nullable|string',
            'attachment'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'note'         => 'nullable|string',
            'to_year'      => 'nullable|digits:4',
            'to_month'     => 'nullable|integer|min:1|max:12',
        ]);
        $validated =  [
            'flat_type_id' => $data['flat_id'],
            'from_year' => $data['year'],
            'from_month' => $data['month'],
            'to_year' => $data['to_year'],
            'to_month' => $data['to_month'],
        ];
        $getFlatMaintaianceDetails = $this->maintenanceCalculatorService->calculate($validated, $this->userId);
        //dd($getFlatMaintaianceDetails);
        // Build description
        $message = "Maintenance payment made from {$data['year']} month: {$data['month']}";
        if (!empty($data['to_year']) && !empty($data['to_month'])) {
            $message .= " to Year: {$data['to_year']} month: {$data['to_month']}";
        }

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('maintenance', $filename, 'public');
            $data['attachment'] = $filename;
        }

        // Pre-load flat & building
        $flat = Flat::with('building')->findOrFail($data['flat_id']);
        $data['building_id'] = $flat->building->id;
        $data['society_id'] = $this->userId;

        // -------- DATE RANGE HANDLING --------
        $start = Carbon::create($data['year'], $data['month'], 1);
        $end   = (!empty($data['to_year']) && !empty($data['to_month']))
            ? Carbon::create($data['to_year'], $data['to_month'], 1)
            : Carbon::create($data['year'], $data['month'], 1);

        // Validate date range
        if ($start->gt($end)) {
            return back()->with('error', 'Invalid date range');
        }

        DB::beginTransaction();
        $perMonthLateFees = $getFlatMaintaianceDetails['monthly_breakup'][0]['late_fee'] ?? 0;
        try {
            // CREATE CASH TRANSACTION (only once)
            $cashRecord = CashTransaction::create([
                'society_id'        => $this->userId,
                'cash_category_id'  => 1,
                'transaction_date'  => now(),
                'transaction_type'  => 'income',
                'amount'            => $getFlatMaintaianceDetails['base_amount'] * count($getFlatMaintaianceDetails['monthly_breakup']),
                'payment_mode'      => $data['payment_mode'],
                'reference_no'      => $request->reference_no,
                'description'       => $message,
                'created_by'        => $this->userId,
            ]);
            if ($getFlatMaintaianceDetails['late_fees_total'] > 0) {
                $lateFees = CashTransaction::create([
                    'society_id'        => $this->userId,
                    'cash_category_id'  => 3,
                    'transaction_date'  => now(),
                    'transaction_type'  => 'income',
                    'amount'            => $getFlatMaintaianceDetails['late_fees_total'],
                    'payment_mode'      => $data['payment_mode'],
                    'reference_no'      => $cashRecord->id,
                    'description'       => "Late Fees " . $message,
                    'created_by'        => $this->userId,
                ]);
            }


            while ($start->lte($end)) {
                $year  = $start->year;
                $month = $start->month;

                // Check if already paid
                if (
                    MaintenanceRecord::where([
                        'flat_id' => $data['flat_id'],
                        'year'    => $year,
                        'month'   => $month,
                        'status'  => 'paid'
                    ])->exists()
                ) {
                    DB::rollBack();
                    return back()->with('error', "Maintenance already PAID for {$month}/{$year}");
                }

                // Create maintenance if not exists
                $maintenance = MaintenanceRecord::firstOrCreate(
                    [
                        'flat_id' => $data['flat_id'],
                        'year'    => $year,
                        'month'   => $month,
                    ],
                    [
                        'society_id'          => $this->userId,
                        'building_id'         => $data['building_id'],
                        'amount'              => $getFlatMaintaianceDetails['base_amount'] + $perMonthLateFees,
                        'attachment'          => $data['attachment'] ?? null,
                        'cash_transactions_id' => $cashRecord->id,
                        'status'              => 'pending',
                        'created_by'          => $this->userId,
                    ]
                );

                // Mark as paid
                $maintenance->update([
                    'status'      => 'paid',
                    'paid_on'     => now(),
                    'updated_by'  => $this->userId,
                ]);

                $start->addMonth();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('society.maintenance.index')
            ->with('success', 'Maintenance added successfully.');
    }

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
    public function edit(MaintenanceRecord $maintenance)
    {
        $buildings = Building::where('society_id', $this->userId)->select('name', 'id')->get();
        $flats = Flat::where('society_id',  $this->userId)->get();
        return view('society.maintenance.edit', compact('flats', 'buildings', 'maintenance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MaintenanceRecord $maintenance)
    {
        $data = $request->validate([
            'amount'       => 'required|numeric',
            'payment_mode' => 'required|in:online,cash,cheque,dd',
            'check_no'     => 'nullable|string',
            'attachment'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'note'         => 'nullable|string',
        ]);

        if ($request->hasFile('attachment')) {

            if ($maintenance->attachment) {
                Storage::disk('public')->delete('maintenance/' . $maintenance->attachment);
            }

            $file = $request->file('attachment');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $file->storeAs('maintenance', $filename, 'public');
            $data['attachment'] = $filename;
        }

        $data['updated_by'] = $this->userId;

        $maintenance->update($data);

        return redirect()->route('society.maintenance.index')
            ->with('success', 'Maintenance updated successfully');
    }

    /**
     * Mark as paid manually
     */
    public function markPaid(MaintenanceRecord $maintenance)
    {
        $maintenance->update([
            'status'   => 'paid',
            'paid_on'  => now(),
            'updated_by' => Auth::user()->name,
        ]);

        return back()->with('success', 'Marked as paid');
    }

    public function calculateMaintenance(Request $request)
    {
        $validated = $request->validate([
            'flat_type_id' => 'required|exists:society_flat_types,id',
            'from_year' => 'required|integer',
            'from_month' => 'required|integer|min:1|max:12',
            'to_year' => 'nullable|integer',
            'to_month' => 'nullable|integer|min:1|max:12',
        ]);

        $result = $this->maintenanceCalculatorService->calculate($validated, $this->userId);

        return response()->json($result);
    }
    /**
     * Delete maintenance record
     */
    public function destroy(MaintenanceRecord $maintenance)
    {
        if ($maintenance->attachment) {
            Storage::disk('public')->delete('maintenance/' . $maintenance->attachment);
        }

        $maintenance->delete();

        return back()->with('success', 'Record deleted successfully');
    }
}
