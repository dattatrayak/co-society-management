<?php

namespace App\Http\Controllers\Society;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Flat;
use App\Models\SocietyFlatType;
use App\Models\SocietyMember;
use App\Models\SocietyUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SocietyMemberController extends Controller
{

    protected $societyUserId; 
    public function __construct( )
    {
        $this->societyUserId = Auth::guard('society_user')->user()->id; 
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $members = SocietyMember::with('society', 'building')->paginate(10);
        return view('society.society_members.index', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $societies = SocietyUser::where('id',$this->societyUserId)->get();
        $buildings = Building::all();
        $societyFlatTypes = SocietyFlatType::pluck('name', 'id');
        $flats = Flat::where('society_id',$this->societyUserId)->pluck('flat_no', 'id');
        // dump($flat);
        return view('society.society_members.create', compact('societies', 'buildings','societyFlatTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'society_id' => 'required|exists:societies,id',
            'building_id' => 'required|exists:buildings,id',
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'permanent_address' => 'required|string|max:255',
            'pan' => 'nullable|string|max:10',
            'uid' => 'nullable|string|max:12',
            'email' => 'required|email|unique:society_members,email',
            'password' => 'required|min:8',
            'mobile' => 'required|digits:10',
            'gender' => 'nullable|in:Male,Female,Other',
            'flat_no' => 'required|string',
            'flat_type' => 'required|in:1 BHK,2 BHK,3 BHK,1 RK',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        SocietyMember::create($validated);

        return redirect()->route('society.society_members.index')->with('success', 'Member created successfully!');
    }

    public function edit(SocietyMember $societyMember)
    {
        $societies = SocietyUser::all();
        $buildings = Building::all();
        return view('society.society_members.edit', compact('societyMember', 'societies', 'buildings'));
    }

    public function update(Request $request, SocietyMember $societyMember)
    {
        $validated = $request->validate([
            'society_id' => 'required|exists:societies,id',
            'building_id' => 'required|exists:buildings,id',
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'permanent_address' => 'required|string|max:255',
            'pan' => 'nullable|string|max:10',
            'uid' => 'nullable|string|max:12',
            'email' => 'required|email|unique:society_members,email,' . $societyMember->id,
            'mobile' => 'required|digits:10',
            'gender' => 'nullable|in:Male,Female,Other',
            'flat_no' => 'required|string',
            'flat_type' => 'required|in:1 BHK,2 BHK,3 BHK,1 RK',
        ]);

        $societyMember->update($validated);

        return redirect()->route('society.society_members.index')->with('success', 'Member updated successfully!');
    }

    public function destroy(SocietyMember $societyMember)
    {
        $societyMember->delete();

        return redirect()->route('society.society_members.index')->with('success', 'Member deleted successfully!');
    }
}