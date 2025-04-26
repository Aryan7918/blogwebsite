<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;



class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(!Auth::user()->can('create_post'), 403, 'You do not have permission to create Permission');
        $permissions = Permission::all();
        return view('admin.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(!Auth::user()->can('create_post'), 403, 'You do not have permission to create Permission');
        return view('admin.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(!Auth::user()->can('create_post'), 403, 'You do not have permission to create Permission');
        $attribute = $request->validate([
            'name' => 'required|unique:permissions',
        ]);
        Permission::create($attribute);
        return redirect()->route('permissions.index')->with('success', 'Permission created successfully');
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
    public function edit(string $id)
    {
        abort_if(!Auth::user()->can('edit_post'), 403, 'You do not have permission to Update Permission');
        $permission = Permission::find($id);
        return view('admin.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort_if(!Auth::user()->can('edit_post'), 403, 'You do not have permission to Update Permission');
        $attribute = $request->validate([
            'name' => 'required|unique:permissions,name,' . $id,
        ]);
        Permission::find($id)->update($attribute);
        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort_if(!Auth::user()->can('delete_post'), 403, 'You do not have permission to Delete Permission');
        Permission::destroy($id);
        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully');
    }
}
