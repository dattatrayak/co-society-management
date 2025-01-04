<?php

namespace App\Http\Controllers\Society;

use App\Http\Controllers\Controller;
use App\Models\building;
use App\Models\Flat;
use App\Models\SocietyFlatType;
use App\Models\SocietyUser;
use App\Models\SocietyUserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FlatController extends Controller
{

    protected $societyUserId;
    protected $flatRepository;
    public function __construct()
    {
        $this->societyUserId = Auth::guard('society_user')->user()->id;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buildings = Building::where('society_id', $this->societyUserId)->select('name', 'id')->get();
        $societyFlatType = SocietyFlatType::select('name', 'id')->get();
        $search = request('search');
        // $flats =  Flat::with('society', 'building', 'flatType')
        // ->when($search, function ($query, $search) {
        //     $query->where('name', 'LIKE', "%{$search}%") // Adjust 'name' to your searchable field
        //           ->orWhere('flat_number', 'LIKE', "%{$search}%") // Add more fields if needed
        //           ->orWhereHas('society', function ($q) use ($search) {
        //               $q->where('name', 'LIKE', "%{$search}%");
        //           })
        //           ->orWhereHas('building', function ($q) use ($search) {
        //               $q->where('name', 'LIKE', "%{$search}%");
        //           })
        //           ->orWhereHas('flatType', function ($q) use ($search) {
        //               $q->where('type', 'LIKE', "%{$search}%");
        //           });
        // })
        // ->paginate(12);
        $search = $request->input('search', '');
        $building_id = $request->input('building_id', null);
        $flat_type = $request->input('flat_type', null);
        $flats = Flat::query();

        // Filter by search term (flat_no or description)
        if (!empty($search)) {
            $flats->where('flat_no', 'LIKE', "%{$search}%")
                  ->orWhere('desc', 'LIKE', "%{$search}%");
        }

        // Filter by building if selected
        if ($building_id) {
            $flats->where('building_id', $building_id);
        }

        // Filter by flat type if selected
        if ($flat_type) {
            $flats->where('society_flat_types_id', $flat_type);
        }

        // Paginate results
        $flats = $flats->with(['building', 'flatType', 'society'])->paginate(10);

        // Preserve search parameters in pagination links
        $flats->appends($request->only(['search', 'building_id', 'flat_type']));

        return view('society.flat.index', compact('flats','buildings','societyFlatType'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $buildings = Building::where('society_id', $this->societyUserId)->select('name', 'id')->get();
        $societyFlatType = SocietyFlatType::select('name', 'id')->get();
        return view('society.flat.create', compact('buildings', 'societyFlatType'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'flat_no' => 'required|string|max:255',
            'building_id' => 'required|numeric',
            'society_flat_types_id' => 'required|numeric',
            'floor_number' => 'required|numeric',
            'desc' => 'nullable|string|max:500',
        ]);
        $flat = $request->all();

        $flat['society_id'] = $this->societyUserId;
        $flats = Flat::create($flat);
        return redirect()->route('society.flat.index')->with('success', 'Society created successfully.');
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
    public function edit(Flat $flat)
    {
        $buildings = Building::where('society_id', $this->societyUserId)->select('name', 'id')->get();
        $societyFlatType = SocietyFlatType::select('name', 'id')->get();
        return view('society.flat.edit', compact('flat', 'societyFlatType','buildings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Flat $flat)
    {
        $request->validate([
            'flat_no' => 'required|string|max:255',
            'building_id' => 'required|numeric',
            'society_flat_types_id' => 'required|numeric',
            'floor_number' => 'required|numeric',
            'desc' => 'nullable|string|max:500',
        ]);

       // $flat = $request->all();
        $flat->update($request->all());

        return redirect()->route('society.flat.index')->with('success', 'Flats updated successfully.');
    }
    public function getFlatsByBuilding($building_id,)
    {
        $flats = Flat::where('society_id', $this->societyUserId)
        ->where('building_id', $building_id)
        ->with('flatType:id,name') // Load only 'id' and 'name' from flatType
        ->get(['id', 'flat_no', 'society_flat_types_id']);
        $flatsData = $flats->map(function ($flat) {
            return [
                'id' => $flat->id,
                'flat_no' => $flat->flat_no,
                'flatType' => $flat->flatType->name, // Accessing the flatType's name
            ];
        });
        
        return response()->json($flatsData);
    }

    // public function getFlatsByBuilding($building_id,$flatNo)
    // {
    //     $flats = Flat::where('building_id', $building_id)->where('flat_no', 'LIKE', '%' . $flatNo . '%')->take(10)->get(['id', 'flat_no']);
    
    //     return response()->json($flats);
    // }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SocietyUser $societyUser)
    {
        // $userType->delete();

        // return redirect()->route('user-types.index')->with('success', 'User type deleted successfully.');
    }
}
