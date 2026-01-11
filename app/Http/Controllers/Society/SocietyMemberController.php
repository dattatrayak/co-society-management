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
        $validated['society_id'] = $this->societyUserId;
        $validated['created_by'] = $this->societyUserId;
        $society_member = SocietyMember::create($validated);
        $society_member->flats()->attach($validated['flat_no']);
        return redirect()->route('society.member.index')->with('success', 'Member created successfully!');
    }

    public function edit(SocietyMember $member)
    {

       $members = SocietyMember::with('society','flats')->paginate(10);
        $societies = SocietyUser::all();
        $buildings = Building::all();
$member->flats()->sync([2, 4, 6]);
         $flats = Flat::with('building', 'flatType')
            ->where('society_id', $this->societyUserId)
            // ->whereNull('society_member_id')
            ->orderBy('flat_no', 'asc')
            ->get();
        // $flatSelected = Flat::where('society_member_id', $member->id)->select('id')->get()->toArray();
        // $member['society_member_id'] = array_map(function ($item) {
        //     return $item['id'];
        // }, $flatSelected);
        return view('society.society_members.edit', compact('member', 'societies', 'buildings', 'flats'));
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
