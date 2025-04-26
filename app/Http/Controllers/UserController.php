<?php

namespace App\Http\Controllers;

use App\DataTables\ModuleDataTable;
use App\DataTables\UsersDataTable;
use App\Http\Requests\UserRequest;
use App\Mail\welcomeMail;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

// use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(UsersDataTable $dataTable)
    {
        abort_if(!Auth::user()->can('view_user'), 403);
        return $dataTable->render('admin.users.index');
    }

    public function create()
    {
        abort_if(!Auth::user()->can('create_user'), 403, 'You do not have permission to create user');
        return view('admin.users.create');
    }

    public function store(UserRequest $request)
    {
        abort_if(!Auth::user()->can('create_user'), 403, 'You do not have permission to create user');
        try {

            $credential = $request->validated();
            $credential['password'] = bcrypt($credential['password']);
            $user = User::create($credential);
            $user->assignRole('reader');

            return redirect(route('users.index'))->with('success', 'User Created Successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit(string $id)
    {
        abort_if(!Auth::user()->can('edit_user'), 403, 'You do not have permission to edit user data');
        $user = User::find($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        abort_if(!Auth::user()->can('edit_user'), 403, 'You do not have permission to edit user data');

        $user = User::find($id);
        $updatevalue = $request->validate([
            'fname' => 'required|min:2',
            'lname' => 'required|min:2',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'mobile' => 'required|min:10',
            'birthdate' => 'required|date',
            'password' => 'required|min:6',
            'status' => 'required',
            'user_role' => 'required'
        ]);
        $res = $user->update($updatevalue);
        // dd("hehha");
        if ($res == true) {
            return redirect(route('users.index'))->with('success', 'User Updated Successfully');
        } else {
            return redirect()->back()->with('danger', 'Failed to Update User');
        }
    }

    public function destroy(string $id)
    {

        abort_if(!Auth::user()->can('delete_user'), 403, 'You do not have permission to delete user data');
        $user = User::withTrashed()->find($id);
        if ($user->trashed()) {
            $user->forceDelete();
            return response()->json(['message' => 'User Deleted Permenantly']);
        }
        $user->delete();
        return response()->json(['message' => 'User Deleted Successfully']);
    }

    public function statusUpdate(Request $request)
    {
        abort_if(!Auth::user()->can('edit_user'), 403, 'You do not have permission to update user status');
        $user = User::find($request->id);
        $user->status = $request->status;
        $user->save();
        return response()->json(['message' => 'User status updated successfully']);
    }

    public function assignRoleToUser(Request $request)
    {
        abort_if(!Auth::user()->can('edit_user'), 403, 'You do not have permission to assign role');
        $user = User::find($request->id);
        $role = $request->role;
        $user->syncRoles($role);

        return response()->json([
            'status' => 'success',
            'message' => 'Role successfully updated '
        ]);
    }

    public function showUserPermission(ModuleDataTable $dataTable)
    {
        return $dataTable->render('admin.modules.index');
    }

    public function givePermissionToUser(Request $request)
    {
        abort_if(!Auth::user()->can('edit_user'), 403, 'You do not have permission to assign role');
        $user = User::find($request->id);
        $permission = $request->permission;
        if (!$user->hasPermissionTo($permission)) {
            $user->givePermissionTo($permission);
        } else {
            $user->revokePermissionTo($permission);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Permission successfully updated '
        ]);
    }

    public function restoreUser(Request $request)
    {
        $user = User::onlyTrashed()->find($request->id);
        $user->restore();
        return response()->json(['message' => 'User Restored Successfully']);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        User::whereIn('id', $ids)->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

    public function bulkUpdateStatus(Request $request)
    {
        $ids = $request->ids;
        User::whereIn('id', $ids)->update(['status' => $request->status]);
        return response()->json(['message' => 'User status updated successfully']);
    }
    public function bulkRestore(Request $request)
    {
        try {
            $ids = $request->ids;
            User::onlyTrashed()->whereIn('id', $ids)->restore();
            return response()->json(['message' => 'User Restored Successfully']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}
