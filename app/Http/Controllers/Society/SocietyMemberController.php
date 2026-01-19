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
use Illuminate\Support\Facades\DB;

class SocietyMemberController extends Controller
{

    protected $societyUserId;
    public function __construct()
    {
        $this->societyUserId = Auth::guard('society_user')->user()->id;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $members = SocietyMember::with('society')->paginate(10);
        //$members = [];
        return view('society.society_members.index', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $societies = SocietyUser::where('id', $this->societyUserId)->get();
        $buildings = Building::all();
        $societyFlatTypes = SocietyFlatType::pluck('name', 'id');
        //$flats = Flat::where('society_id', $this->societyUserId)->pluck('flat_no', 'id');
        $flats = Flat::with('building', 'flatType')
            ->where('society_id', $this->societyUserId)
            // ->whereNull('society_member_id')
            ->orderBy('flat_no', 'asc')
            ->get();

        return view('society.society_members.create', compact('societies', 'buildings', 'societyFlatTypes', 'flats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'permanent_address' => 'required|string|max:255',
            'pan' => 'nullable|string|max:10',
            'uid' => 'nullable|string|max:12',
            'email' => 'required|email|unique:society_members,email',
            //'password' => 'required|min:8',
            'mobile' => 'required|digits:10',
            'gender' => 'nullable|in:Male,Female',
            'flat_no' => 'required|array',
        ]);
        $lastFiveDigits =  $validated['mobile'];
        $validated['password'] = bcrypt($lastFiveDigits);
        $validated['society_id'] = $this->societyUserId;
        $validated['created_by'] = $this->societyUserId;
        $society_member = SocietyMember::create($validated);
        $society_member->flats()->attach($validated['flat_no']);
        return redirect()->route('society.member.index')->with('success', 'Member created successfully!');
    }

    public function edit(SocietyMember $member)
    {

        $societies = SocietyUser::all();
        $buildings = Building::all();
        $member->flats();
        $flats = Flat::with('building', 'flatType')
            ->where('society_id', $this->societyUserId)
            // ->whereNull('society_member_id')
            ->orderBy('flat_no', 'asc')
            ->get();
        $selectedFlats = $member->flats->pluck('id')->toArray();
        
        return view('society.society_members.edit', compact('member', 'societies', 'buildings', 'flats', 'selectedFlats'));
    }

    public function update(Request $request, SocietyMember $member)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'permanent_address' => 'required|string|max:255',
            'pan' => 'nullable|string|max:10',
            'uid' => 'nullable|string|max:12',
            'email' => 'required|email|unique:society_members,email,' . $member->id,
            //'password' => 'required|min:8',
            'mobile' => 'required|digits:10',
            'gender' => 'nullable|in:Male,Female',
            'flat_no' => 'required|array',
        ]);
        $member->update($validated);
        $member->flats()->sync($validated['flat_no']);
        // DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        // Flat::where('society_member_id', $member->id)->update(['society_member_id' =>null]);
        // foreach ($validated['flat_no'] as $flatNo) {
        //     //DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        //     Flat::where('id', $flatNo)->update(['society_member_id' => $member->id]);
        //     //
        // }
        // DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        return redirect()->route('society.member.index')->with('success', 'Member updated successfully!');
    }

    public function destroy(SocietyMember $societyMember)
    {
        $societyMember->delete();

        return redirect()->route('society.member.index')->with('success', 'Member deleted successfully!');
    }
}
