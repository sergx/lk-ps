<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
// php artisan db:seed --class=RolePermissionAdminSeeder
class RolePermissionAdminSeeder extends Seeder {

  public function run() {
    //
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    Permission::create(['name' => 'admin only']);

    $role_admin = Role::create(['name' => 'admin']);
    $role_admin->givePermissionTo('admin only');

    $user = \App\Models\User::factory()->create([
      'name' => 'Сергей',
      'email' => 'admin@pioneer-school.ru',
      'password' => Hash::make("h67ha7h567h"),
    ]);
    $user->assignRole($role_admin);
  }
}
