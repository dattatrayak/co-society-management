<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocietyUserType;
use Illuminate\Http\Request;

class SocietyUserTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $societyUserTypes = SocietyUserType::all();
        return view('admin.society_user_type.index', compact('societyUserTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.society_user_type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:society_user_types,name',
        ]);
        SocietyUserType::create(['name' => $request->name]);

        return redirect()->route('admin.society-user-types.index')->with('success', 'User type created successfully.');
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
    public function edit(SocietyUserType $societyUserType)
    {
        return view('admin.society_user_type.edit', compact('societyUserType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SocietyUserType $societyUserType)
    {
        $request->validate([
            'name' => 'required|unique:society_user_types,name,' . $societyUserType->id,
        ]);

        $societyUserType->update(['name' => $request->name]);

        return redirect()->route('admin.society-user-types.index')->with('success', 'User type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SocietyUserType $societyUserType)
    {
        // $societyUserType->delete();

        // return redirect()->route('society-user-types.index')->with('success', 'User type deleted successfully.');
    }
}
