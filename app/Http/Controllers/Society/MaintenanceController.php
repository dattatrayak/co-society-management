<?php

namespace App\Http\Controllers\Society;

use App\Http\Controllers\Controller;
use App\Http\Repository\BuildingRepository;
use App\Http\Repository\MeterRepository;
use App\Models\Building;
use App\Models\ElectricityMeter;
use App\Models\Flat;
use App\Models\MaintenanceRecord;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaintenanceController extends Controller
{
    private $userId = null;
    private $meterRepository = null;
    private $buildingRepository = null;

    public function __construct(MeterRepository $meterRepository, BuildingRepository $buildingRepository)
    {
        $societyUser = Auth::guard('society_user')->user();
        $this->userId = $societyUser->id;
        $this->meterRepository = $meterRepository;
        $this->buildingRepository = $buildingRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buildings = Building::where('society_id', $this->userId)->select('name', 'id')->get();
        $records = MaintenanceRecord::with(['flat', 'building'])
            ->when($request->year, fn($q) => $q->where('year', $request->year))
            ->when($request->month, fn($q) => $q->where('month', $request->month))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->paginate(15);
           
        return view('society.maintenance.index', compact('records', 'buildings'));
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

    /**
     * Store a newly created resource in storage.
     */
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
        ]);
        $data['society_id'] = $this->userId;
        $flat = Flat::with('building')->findOrFail($data['flat_id']);
        $data['building_id'] = $flat->building->id;
        
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $file->storeAs('maintenance', $filename, 'public');
            $data['attachment'] = $filename;
        }

        $data['status'] = 'paid';
        $data['paid_on'] = now();
        $data['created_by'] = $this->userId;

       try {
    MaintenanceRecord::create($data);
} catch (QueryException $e) {
    if ($e->errorInfo[1] == 1062) {
        return back()
            ->withInput()
            ->with('error', 'Maintenance already exists for this flat and month.');
    }
    throw $e;
}


        return redirect()->route('society.maintenance.index')->with('success', 'Maintenance added successfully.');
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
