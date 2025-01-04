<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        return view('admin.menus.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $menus = Menu::whereNull('parent_id')->get(); // For parent dropdown
        return view('admin.menus.create', compact('menus'));
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

        Menu::create($request->all());

        return redirect()->route('admin.menus.index')->with('success', 'Menu created successfully.');
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
    public function edit(Menu $menu)
    {
        $menus = Menu::whereNull('parent_id')->with('children.children')->orderBy('order')->get();

        return view('admin.menus.edit', compact('menu', 'menus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'nullable|string',
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'required|integer',
        ]);

        $menu->update($request->all());

        return redirect()->route('admin.menus.index')->with('success', 'Menu updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('admin.menus.index')->with('success', 'Menu deleted successfully.');
    }
}
