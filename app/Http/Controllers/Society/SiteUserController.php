<?php

namespace App\Http\Controllers\Society;

use App\Http\Controllers\Controller;
use App\Models\SocietyUser;
use App\Models\SocietyUserType;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SiteUserController extends Controller
{
    public function dashboard(){
        return view('society.dashboard.index');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

       $societies = SocietyUser::all();
        return view('society.society_user.index', compact('societies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $societyUserTypes = SocietyUserType::all();
        return view('society.society_user.create', compact('societyUserTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'reg_no' => 'nullable|numeric',
            'reg_year' => 'nullable|digits:4|integer|min:1900|max:' . date('Y'),
            'address' => 'required|string|max:500',
            'desc' => 'nullable|string|max:1000',
            'mobile_no' => 'required|regex:/^\+?[0-9]{10,15}$/|unique:society_users,mobile_no',

            'email' => 'required|email|unique:society_users,email',
            'password' => 'required|confirmed|min:8',
            'building_count' => 'required|integer|min:1',
            'lift_count' => 'nullable|integer|min:1',
            'meter_count' => 'required|integer|min:1',
            'logo' => 'nullable|image|mimes:jpg,png,jpeg,svg|max:2048',
            'file2' => 'nullable|image|mimes:jpg,png,jpeg,svg|max:2048',
        ]);
        $updatedRequest = $request->all();
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileName = time() . '-' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/society/logo', $fileName, 'public'); // Stored in storage/app/public/uploads
        $updatedRequest['logo'] = $fileName;
        }

        if ($request->hasFile('society_image')) {
            $file = $request->file('society_image');
            $fileName = time() . '-' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/society/img', $fileName, 'public'); // Stored in storage/app/public/uploads
           // $request->merge(['society_image' => $fileName]);
            $updatedRequest['society_image'] = $fileName;
        }

        //$allData->password = Hash::make($request->password);
        SocietyUser::create($updatedRequest);

        return redirect()->route('society.society-user.index')->with('success', 'Society created successfully.');
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
    public function edit(SocietyUser $societyUser)
    {
        $societyUserTypes = SocietyUserType::all();
        return view('society.society_user.edit', compact('societyUser','societyUserTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SocietyUser $societyUser)
    {
        $request->validate([
            'name' => 'required|unique:user_types,name,' . $societyUser->id,
        ]);


        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileName = time() . '-' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/society/logo', $fileName, 'public'); // Stored in storage/app/public/uploads
           // $request->merge(['logo' => $fileName]);
            $societyUser->logo = $fileName;
            if($societyUser->logo){
                $imagePath = Storage::url('public/uploads/society/logo/' . $societyUser->logo);
                File::delete($imagePath);
            }
        }

        if ($request->hasFile('society_image')) {
            $file = $request->file('society_image');
            $fileName = time() . '-' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/society/img', $fileName, 'public'); // Stored in storage/app/public/uploads
            //$request->merge(['' => $fileName]);
            $societyUser->society_image = $fileName;
            if($societyUser->society_image){
                $imagePath = Storage::url('public/uploads/society/logo/' . $societyUser->society_image);
                File::delete($imagePath);
            }
        }
        if(! $request->input('password'))
            $request->request->remove('password');



        $societyUser->update($request->all());

        return redirect()->route('society.society-user.index')->with('success', 'User type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SocietyUser $societyUser)
    {
       // $userType->delete();

       // return redirect()->route('user-types.index')->with('success', 'User type deleted successfully.');
    }
}
