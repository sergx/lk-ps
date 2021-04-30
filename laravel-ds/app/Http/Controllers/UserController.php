<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisterController as RegisterUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;

class UserController extends Controller {

  public function getUsersWithRoles() {

    $roles = Role::all();
    $users = User::with(['roles'])->get();

    return [
      'roles' => $roles,
      'users' => $users,
    ];
  }

  public function create(Request $request) {
    $RegisterUser = new RegisterUser();
    $RegisterUser->validator($request->all())->validate();

    $user = $RegisterUser->create(array_merge(
      $request->only(['name', 'email', 'phone', 'child']),
      ['password' => 'default_password']
    ));
    return [
      'notify' => ['text' => 'Пользователь <strong>' . $user->name . '</strong> создан!']
    ];
  }

  public function update(Request $request) {
    $this->validate($request, [
      'id' => ['required', 'numeric', 'min:1'],
      'name' => ['required', 'string', 'max:191'],
      'child' => ['required', 'string', 'max:191'],
      'email' => ['required', 'string', 'email', 'max:191', Rule::unique('users')->ignore($request->id)],
    ]);
    $user = User::find($request->id);
    $user->update($request->only('name', 'child', 'phone', 'email'));
    if (!empty(trim($request->password))) {
      $user->password = Hash::make(trim($request->password));
      $user->save();
    }

    return [
      'user' => $user,
      'notify' => ['text' => 'Пользователь обновлен!']
    ];
  }

  // TODO OMG WTF...
  public function authData() {
    $user = Auth::user();
    return [
      'user' => collect($user)->except('permissions', 'roles'),
      'permissions' => $user->getAllPermissions()->pluck("name"),
    ];
  }

  public function userData(Request $request) {
    $user = User::find($request->id);
    return [
      'user' => collect($user)->except('permissions', 'roles'),
      'permissions' => $user->getAllPermissions()->pluck("name"),
    ];
  }

  public function destroy(Request $request) {
    //return ['asdsa'];
    $user = User::where('id', $request->id)->with(['QuizAnswer'])->first();
    $user->delete();
    return [
      //'user' => $user,
      'notify' => ['text' => 'Пользователь удален']
    ];
  }
}
