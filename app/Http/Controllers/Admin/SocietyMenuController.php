<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SocietyMenu;

class SocietyMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $societyMenu = SocietyMenu::whereNull('parent_id')->with('children')->orderBy('order')->get();


        return view('admin.society_menus.index', compact('societyMenu'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $societyMenu = SocietyMenu::whereNull('parent_id')->get(); // For parent dropdown
        return view('admin.society_menus.create', compact('societyMenu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'page_heading' => 'nullable|string|max:255',
            'url' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menus,id',
            'sub_heading' => 'nullable|string|max:255',
            'order' => 'required|integer',
        ]);

        SocietyMenu::create($request->all());

        return redirect()->route('admin.society-menus.index')->with('success', 'Menu created successfully.');
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
    public function edit(SocietyMenu $societyMenu)
    {
        $societyMenuEdit= $societyMenu;
        $societyMenuList = SocietyMenu::whereNull('parent_id')->with('children.children')->orderBy('order')->get();

        return view('admin.society_menus.edit', compact('societyMenuEdit','societyMenuList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SocietyMenu $societyMenu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'nullable|string',
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'required|integer',
        ]);

        $societyMenu->update($request->all());

        return redirect()->route('admin.society-menus.index')->with('success', 'Menu updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SocietyMenu $societyMenu)
    {
        $societyMenu->delete();
        return redirect()->route('admin.society-menus.index')->with('success', 'Menu deleted successfully.');
    }

}
