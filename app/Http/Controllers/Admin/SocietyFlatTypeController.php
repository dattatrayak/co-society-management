<?php

namespace App\Http\Controllers\Admin;

use App\Models\SocietyFlatType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class SocietyFlatTypeController extends Controller
{
   /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userTypes = SocietyFlatType::all();
        return view('admin.society_flat_types.index', compact('userTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.society_flat_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:society_flat_types,name',
        ]);

        SocietyFlatType::create(['name' => $request->name]);

        return redirect()->route('admin.society-flat-type.index')->with('success', 'User type created successfully.');
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
    public function edit(SocietyFlatType $userType)
    {
        return view('admin.society_flat_types.edit', compact('userType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SocietyFlatType $societyFlatType)
    {
        $request->validate([
            'name' => 'required|unique:user_types,name,' . $societyFlatType->id,
        ]);

        $societyFlatType->update(['name' => $request->name]);

        return redirect()->route('admin.society-flat-type.index')->with('success', 'User type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SocietyFlatType $societyFlatType)
    {
       // $userType->delete();

       // return redirect()->route('society_flat_types.index')->with('success', 'User type deleted successfully.');
    }
}
