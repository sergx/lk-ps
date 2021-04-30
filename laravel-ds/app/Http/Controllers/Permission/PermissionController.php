<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionController extends Controller {
  public function __construct() {
    //$this->middleware(['auth', 'role:admin']);
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
  }

  public function getPermissionsAndRoles() {
    $roles = Role::with(['permissions' => function ($query) {
      $query->orderBy('name', 'asc');
    }])->orderBy('name', 'asc')->get();
    $permissions = Permission::with(['roles'])->orderBy('name', 'asc')->get();
    $permissions_pluck = Permission::all()->pluck('name', 'id');
    //dd($permissions);
    //return Inertia::render('Auth/Register');

    return [
      'roles' => $roles,
      'permissions' => $permissions,
      'permissions_pluck' => $permissions_pluck
    ];
  }
  public function addPermission(Request $request) {
    $this->validate($request, [
      'permission' => 'required'
    ]);

    Permission::create(['name' => $request->permission]);
    return response()->json(['notify' => ['type' => 'success', 'text' => 'Permission ' . $request->permission . ' added']]);
  }

  public function addRole(Request $request) {
    $this->validate($request, [
      'role' => 'required'
    ]);
    Role::create(['name' => $request->role]);
    return response()->json(['notify' => ['type' => 'success', 'text' => 'Role ' . $request->permission . ' added']]);
  }

  public function assignPermissionToRole(Request $request) {
    $role = Role::find($request->role_id);
    $permission = Permission::find($request->permission_id);
    $role->givePermissionTo($request->permission_id);
    return response()->json(['notify' => ['type' => 'success', 'text' => "Permission " . $permission->name . " granded to role " . $role->name]]);
  }

  public function revokePermissionFromRole(Request $request) {
    $role = Role::find($request->role_id);
    $role->revokePermissionTo($request->permission_id);
    return response()->json(['notify' => ['type' => 'success', 'text' => "Permission " . $request->permission . " Revoked from role " . $role->name]]);
  }

  public function removePermission(Request $request) {
    $permission = Permission::find($request->permission_id);
    $permission_name = $permission->name;
    $permission->delete();
    return response()->json(['notify' => ['type' => 'success', 'text' => "Permission " . $permission_name . " removed"]]);
  }

  public function toggleUserRole(Request $request) {

    $user = User::find($request->user_id);

    if($user->hasRole($request->role_name)){
      $user->removeRole($request->role_name);
      $text = " removed from ";
    }else{
      $user->assignRole($request->role_name);
      $text = " added to ";
    }

    $user = User::where('id', $request->user_id)->with(['roles'])->first();

    return response()->json(['user' => $user, 'notify' => ['type' => 'success', 'text' => "Role " . $request->role_name . $text . $user->name]]);

  }

}
