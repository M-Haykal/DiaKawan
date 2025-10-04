<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class UserController extends Controller
{
    // 🔹 Menampilkan daftar user, role, dan permission
    public function index()
    {
        $users = User::with('roles')->simplePaginate(5);
        $roles = Role::all();
        $permissions = Permission::all();

        return view('dashboard.pages.user', compact('users', 'roles', 'permissions'));
    }

    public function assignRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = User::findOrFail($id);
        $user->syncRoles([$request->role]);

        return back()->with('success', 'Role berhasil diberikan ke ' . $user->username);
    }

    public function destroyRole($id, $roleName)
    {
        $user = User::findOrFail($id);
        $user->removeRole($roleName);

        return back()->with('success', 'Role berhasil dihapus dari user');
    }

    // 🔹 Membuat permission baru
    public function storePermission(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:permissions,name']);
        Permission::create(['name' => $request->name, 'guard_name' => 'web']);
        return back()->with('success', 'Permission baru ditambahkan.');
    }

    // 🔹 Memberikan permission ke role
    public function assignPermission(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'array'
        ]);

        $role = Role::find($request->role_id);
        $role->syncPermissions($request->permissions ?? []);

        return back()->with('success', 'Permission berhasil disimpan untuk role ' . $role->name);
    }
}
