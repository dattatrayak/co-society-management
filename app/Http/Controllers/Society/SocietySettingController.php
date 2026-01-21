<?php

namespace App\Http\Controllers\Society;

use App\Http\Controllers\Controller;
use App\Models\SocietyFlatType;
use App\Models\SocietyFlatTypeMaintenance;
use App\Models\SocietySetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SocietySettingController extends Controller
{

    private $userId = null;

    public function __construct()
    {
        $societyUser = Auth::guard('society_user')->user();
        $this->userId = $societyUser->id;
    }
    /**
     * Display settings list
     */
    public function index()
    {
        $maintenances = SocietyFlatTypeMaintenance::with('flatType')->get();
        $flatTypes = SocietyFlatType::where('status', 1)->get();
        $settings = SocietySetting::paginate(10);
        return view('society.society_setting.index', compact('settings', 'maintenances','flatTypes'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $flatTypes = SocietyFlatType::where('status', 1)->get();
        $maintenances = SocietyFlatTypeMaintenance::with('flatType')->get();
        return view('society.society_setting.edit', compact('flatTypes', 'maintenances'));
    }

    /**
     * Store new setting
     */
    public function store(Request $request)
    { 
        $request->validate([
            'society_flat_type_id' => 'required|exists:society_flat_types,id|unique:society_flat_type_maintenances,society_flat_type_id',
            'maintenance_amount'   => 'required|numeric|min:0',
        ]);

        SocietyFlatTypeMaintenance::create($request->all());
        return redirect()
            ->route('society.setting.index')
            ->with('success', 'Society setting created successfully');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
         
        $maintenance = SocietyFlatTypeMaintenance::findOrFail($id);
        $flatTypes = SocietyFlatType::where('status', 1)->get();

        return view('society.society_setting.edit', compact('maintenance', 'flatTypes'));
    }

    /**
     * Update setting
     */
    public function update(Request $request, $id)
    {

        $request->validate([
            'society_flat_type_id' => 'required|exists:society_flat_types,id|unique:society_flat_type_maintenances,society_flat_type_id,' . $id,
            'maintenance_amount'   => 'required|numeric|min:0',
        ]);

        $maintenance = SocietyFlatTypeMaintenance::findOrFail($id);
        $maintenance->update($request->all());

        return redirect()
            ->route('society.setting.index')
            ->with('success', 'Society setting updated successfully');
    }
    /**
     * Show create form
     */
    public function show($id)
    {
        $setting = SocietySetting::where([
            'id'     => $id,
            'society_id' => $this->userId,
        ])->firstOrFail();
        return response()->json(
            $setting
        );
    }
    /**
     * Update setting
     */
    public function storeOrUpdate(Request $request)
    { 
        $request->validate([
            'maintenance_late_fee' => 'required|numeric|min:0',
            'late_fee_type' => 'required|in:fixed,percentage',
            'grace_days' => 'required|integer|min:0',
        ]);

        SocietySetting::updateOrCreate(
            ['id' => $request->id], // null → create, id → update
            [
                'maintenance_late_fee' => $request->maintenance_late_fee,
                'late_fee_type' => $request->late_fee_type,
                'grace_days' => $request->grace_days,
            ]
        );
  return response()->json([
        'status' => true,
        'message' => 'Society settings saved successfully' 
    ]); 
    }

    /**
     * Delete setting
     */
    public function destroy($id)
    { 
        // SocietyFlatTypeMaintenance::findOrFail($id)->delete();
        return redirect()
            ->route('society.setting.index')
            ->with('success', 'Society setting deleted successfully');
    }
}
