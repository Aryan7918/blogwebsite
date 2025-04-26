<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;


class ModuleController extends Controller
{
    public function index()
    {
        $roles = Role::with('Permissions')->get(['*']);
        return view('admin.modules.index', compact('roles'));
    }
}
