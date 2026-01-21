<?php

namespace App\Http\Controllers\Society;

use App\Http\Controllers\Controller;
use App\Models\SocietyFlatType;
use App\Models\SocietyFlatTypeMaintenance;
use Illuminate\Http\Request;

class FlatTypeMaintenanceController extends Controller
{
    // public function index()
    // {
    //     $maintenances = SocietyFlatTypeMaintenance::with('flatType')->get();
    //     return view('society.flat_type_maintenance.index', compact('maintenances'));
    // }

    public function create()
    {
        $flatTypes = SocietyFlatType::where('status',1)->get();
        return view('society.flat_type_maintenance.create', compact('flatTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'society_flat_type_id' => 'required|exists:society_flat_types,id|unique:society_flat_type_maintenances,society_flat_type_id',
            'maintenance_amount'   => 'required|numeric|min:0',
        ]);

        SocietyFlatTypeMaintenance::create($request->all());

        return redirect()->route('society.setting.index')
            ->with('success','Maintenance amount added successfully');
    }

    public function edit($id)
    {
        $maintenance = SocietyFlatTypeMaintenance::findOrFail($id);
        $flatTypes = SocietyFlatType::where('status',1)->get();

        return view('society.flat_type_maintenance.edit', compact('maintenance','flatTypes'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'society_flat_type_id' => 'required|exists:society_flat_types,id|unique:society_flat_type_maintenances,society_flat_type_id,'.$id,
            'maintenance_amount'   => 'required|numeric|min:0',
        ]);

        $maintenance = SocietyFlatTypeMaintenance::findOrFail($id);
        $maintenance->update($request->all());

        return redirect()->route('society.flat-type-maintenance.index')
            ->with('success','Maintenance amount updated successfully');
    }

    public function destroy($id)
    {
        SocietyFlatTypeMaintenance::findOrFail($id)->delete();

        return redirect()->route('society.flat-type-maintenance.index')
            ->with('success','Maintenance amount deleted');
    }
}