<?php

namespace App\Http\Controllers\Society;

use App\Http\Controllers\Controller;
use App\Http\Repository\BuildingRepository;
use App\Http\Repository\MeterRepository;
use App\Models\Building;
use App\Models\ElectricityMeter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MetterController extends Controller
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
    public function index()
    {

        $meters = ElectricityMeter::with('society')->with('building')->paginate(10);
        return view('society.meter.index', compact('meters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $buildings = $this->buildingRepository->getSocietyBuilding($this->userId);
        return view('society.meter.create', compact('buildings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'electricity_meter' => 'required|unique:electricity_meters,electricity_meter'
        ]);
        $insertData = $request->all();
        $insertData['society_id'] = $this->userId;
        ElectricityMeter::create($request->all());

        return redirect()->route('society.meter.index')->with('success', 'Meter created successfully.');
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
    public function edit(ElectricityMeter $meter)
    {
        $buildings = $this->buildingRepository->getSocietyBuilding($this->userId);
        return view('society.meter.edit', compact('meter', 'buildings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ElectricityMeter $meter)
    {

        $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'electricity_meter' => 'required|unique:electricity_meters,electricity_meter,' . $meter->id
        ]);
        $insert = $request->all();
        $insertData['society_id'] = $this->userId;
        $meter->update($insert);

        return redirect()->route('society.meter.index')->with('success', 'Electricity Meter type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ElectricityMeter $electricityMeter)
    {
        // $userType->delete();

        // return redirect()->route('user-types.index')->with('success', 'User type deleted successfully.');
    }
}
