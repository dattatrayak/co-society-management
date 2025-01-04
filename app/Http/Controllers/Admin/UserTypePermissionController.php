<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\UserType;
use App\Models\UserTypePermission;
use Illuminate\Http\Request;
use App\Http\Repository\MenuRepository;

class UserTypePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $res = new MenuRepository();
        $userTypes = UserType::all();
        $menus = Menu::with('children');
        return view('admin.user_types_permission.index', compact('userTypes', 'menus'));
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
            'permissions' => 'required|array',
        ]);
        foreach ($request->permissions as $menu_id => $menuPermissions) {
            // dump($menuPermissions);
            $menuAccess =  array(
                'user_type_id' => $request->user_type,
                'menu_id' => $menu_id,
                'view' => isset($menuPermissions['view']),
                'add' => isset($menuPermissions['add']),
                'delete' => isset($menuPermissions['delete']),
                'view_own' => isset($menuPermissions['view_own']),
                'delete_own' => isset($menuPermissions['delete_own']),
                'delete_other' => isset($menuPermissions['delete_other']),
            );
            UserTypePermission::updateOrCreate([
                'user_type_id' => $request->user_type,
                'menu_id' => $menu_id,
            ], $menuAccess);
        }

        /*  foreach ($request->permissions as $userTypeId => $menuPermissions) {
            foreach ($menuPermissions as $menuId => $permissions) {
                UserTypePermission::updateOrCreate(
                    [
                        'user_type_id' => $userTypeId,
                        'menu_id' => $menuId,
                    ],
                    [
                        'view' => isset($permissions['view']),
                        'add' => isset($permissions['add']),
                        'delete' => isset($permissions['delete']),
                        'view_own' => isset($permissions['view_own']),
                        'delete_own' => isset($permissions['delete_own']),
                        'delete_other' => isset($permissions['delete_other']),
                    ]
                );
            }
        }*/

        return redirect()->back()->with('success', 'Permissions updated successfully.');
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

    public function getPermissions(Request $request)
    {
        $userTypeId = $request->user_type_id;
        $fillableColumns = (new UserTypePermission)->getFillable();
        // $menus = Menu::with(['userTypePermissions' => function ($query) use ($userTypeId) {
        //     $query->where('user_type_id', $userTypeId);
        // }])->get();
        $menus = UserTypePermission::select($fillableColumns)->where('user_type_id', $userTypeId)->get();
        return response()->json(['menus' => $menus]);
    }
}
