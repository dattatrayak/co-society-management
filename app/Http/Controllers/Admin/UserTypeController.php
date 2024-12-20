<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserType;
use Illuminate\Http\Request;

class UserTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userTypes = UserType::all();
        return view('admin.user_types.index', compact('userTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:user_types,name',
        ]);

        UserType::create(['name' => $request->name]);

        return redirect()->route('admin.user-types.index')->with('success', 'User type created successfully.');
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
    public function edit(UserType $userType)
    {
        return view('admin.user_types.edit', compact('userType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserType $userType)
    {
        $request->validate([
            'name' => 'required|unique:user_types,name,' . $userType->id,
        ]);

        $userType->update(['name' => $request->name]);

        return redirect()->route('admin.user-types.index')->with('success', 'User type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserType $userType)
    {
       // $userType->delete();

       // return redirect()->route('user-types.index')->with('success', 'User type deleted successfully.');
    }
}
