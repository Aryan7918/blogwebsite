<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissionGroups = Permission::orderBy('module_name')->get()->groupBy('module_name');
        $roles = Role::with('Permissions')->get();
        return view('admin.roles.index', compact('roles', 'permissionGroups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(!Auth::user()->can('create_post'), 403, 'You do not have permission to create role');
        return view('admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(!Auth::user()->can('create_post'), 403, 'You do not have permission to create role');
        $attribute = $request->validate([
            'name' => 'required|unique:roles',
        ]);
        Role::create($attribute);
        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $role = Role::find($id);
        // return view('roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort_if(!Auth::user()->can('edit_post'), 403, 'You do not have permission to Update role');
        $role = Role::find($id);
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $id)
    {
        abort_if(!Auth::user()->can('edit_post'), 403, 'You do not have permission to Update role');
        $role = Role::find($id);
        $attribute = $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
        ]);
        $role->update($attribute);
        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort_if(!Auth::user()->can('delete_post'), 403, 'You do not have permission to Delete role');
        $role = Role::find($id);
        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully');
    }

    public function addPermissionToRole(string $id)
    {
        abort_if(!Auth::user()->can('create_post'), 403, 'You do not have permission to Update role');
        $role = Role::find($id);
        $permissions = Permission::all();
        return view('admin.roles.addPermission', compact('role', 'permissions'));
    }

    public function updatePermissionToRole(Request $request)
    {
        abort_if(!Auth::user()->can('edit_post'), 403, 'You do not have permission to Update Role');
        $role = Role::find($request->id);
        $permissions = $request->validate([
            'permissions' => 'required',
        ]);
        $permissions = $request->input('permissions');
        $role->syncPermissions($permissions);
        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully');
    }

    public function givePermissionToRole(Request $request)
    {
        abort_if(!Auth::user()->hasRole('admin'), 403);
        $role = Role::find($request->id);
        $permission = $request->permission;
        // return $role->hasPermissionTo($permission);
        if (!$role->hasPermissionTo($permission)) {
            $role->givePermissionTo($permission);
        } else {
            $role->revokePermissionTo($permission);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'permission successfully updated '
        ]);
    }
}