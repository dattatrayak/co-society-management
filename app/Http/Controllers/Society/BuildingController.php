<?php

namespace App\Http\Controllers\Society;

use App\Http\Controllers\Controller;
use App\Http\Repository\FlatRepository;
use App\Models\Building;
use App\Models\Flat;
use App\Models\SocietyFlatType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BuildingController extends Controller
{
    protected $societyUserId;
    protected $flatRepository;
    public function __construct(FlatRepository $flatRepository)
    {
        $this->societyUserId = Auth::guard('society_user')->user()->id;
        $this->flatRepository = $flatRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $buildings = Building::all();
        return view('society.building.index', compact('buildings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $societyFlatType = SocietyFlatType::pluck('name', 'id');

        return view('society.building.create', compact('societyFlatType'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            // 'name' => 'required|string|max:255',
            'name'  => [ 'required', 'string', 'max:255',
                            Rule::unique('buildings')->where(function ($query) use($request) {
                                $query->where('name', $request->name)->where('society_id', $this->societyUserId);
                            }),
                        ],
            'flat_count' => 'required|integer|between:1,200',
            'floor' => 'required|integer|between:1,30',
            'flat_no_start' => 'required|integer',
            'flat_per_floor' => 'required|integer|between:1,50',
            'society_flat_types_id' => 'required|numeric',
            'cctv' => 'nullable|integer|between:1,30',
            'lift' => 'nullable|integer|between:1,10',
            'maintance_per_month' => 'required|numeric|min:1|max:100000',
            'water_tank' => 'nullable|integer|between:1,10',
            'building_img' => 'nullable|image|mimes:jpg,png,jpeg,svg|max:2048',
            'floor_plan' => 'nullable|image|mimes:jpg,png,jpeg,svg|max:2048',
        ]);

        $updatedRequest = $request->all();

        if ($request->hasFile('building_img')) {
            $file = $request->file('building_img');
            $fileName = time() . '-' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/society/building', $fileName, 'public');
            $updatedRequest['building_img'] = $fileName;
        }

        if ($request->hasFile('floor_plan')) {
            $file = $request->file('floor_plan');
            $fileName = time() . '-' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/society/floor_plan', $fileName, 'public');
            $updatedRequest['floor_plan'] = $fileName;
        }

        $user = Auth::guard('society_user')->user();
        $updatedRequest['society_id'] = $user->id;
        unset($updatedRequest['society_flat_types_id']);
        unset($updatedRequest['maintance_per_month']); 
        $building = Building::create($updatedRequest);

        //insert all flats of society
        $this->flatRepository->insertOrUpdateFlat( $building, $request->all());

        return redirect()->route('society.building.index')->with('success', 'Society created successfully.');
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
    public function edit(Building $building)
    {

       // Flat::upsert($flatEntry, ['society_id', 'building_id', 'flat_no'] );

        $societyFlatType = SocietyFlatType::pluck('name', 'id');
        return view('society.building.edit', compact('building', 'societyFlatType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Building $building)
    {

        $request->validate([
            // 'society_id',
            'name' => 'required|string|max:255',
            'flat_count' => 'required|integer|between:1,200',
            'floor' => 'required|integer|between:1,30',
            'flat_no_start' => 'required|integer',
            'flat_per_floor' => 'required|integer|between:1,50',
            'society_flat_types_id' => 'nullable|numeric',
            'cctv' => 'nullable|integer|between:1,30',
            'lift' => 'nullable|integer|between:1,10',
            'water_tank' => 'nullable|integer|between:1,10',
            'building_img' => 'nullable|image|mimes:jpg,png,jpeg,svg|max:2048',
            'floor_plan' => 'nullable|image|mimes:jpg,png,jpeg,svg|max:2048',
        ]);

        $updatedRequest = $request->all();
        if ($request->hasFile('building_img')) {
            $file = $request->file('building_img');
            $fileName = time() . '-' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/society/building', $fileName, 'public');
            $updatedRequest['building_img'] = $fileName;
            if ($building->logo) {
                $imagePath = Storage::url('public/uploads/society/building/' . $building->building_img);
                File::delete($imagePath);
            }
        }

        if ($request->hasFile('floor_plan')) {
            $file = $request->file('floor_plan');
            $fileName = time() . '-' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/society/floor_plan', $fileName, 'public');
            $updatedRequest['floor_plan'] = $fileName;
            if ($building->floor_plan) {
                $imagePath = Storage::url('public/uploads/society/floor_plan/' . $building->floor_plan);
                File::delete($imagePath);
            }
        }


        $user = Auth::guard('society_user')->user();
        $updatedRequest['society_id'] = $user->id;
        unset($updatedRequest['society_flat_types_id']);
        $building->update($updatedRequest);
            //insert all flats of society
            $this->flatRepository->insertOrUpdateFlat( $building, $request->all());
        return redirect()->route('society.building.index')->with('success', 'User type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(building $building)
    {
        // $userType->delete();

        // return redirect()->route('society.building.index')->with('success', 'User type deleted successfully.');
    }
}