<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\GroupAdmins;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //I used the Roles as a Group since the Task is not that complex
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'moderate_group']);

        $role1 = Role::create(['name' => 'super-admin']);

        $user = User::where('id', 1)->first();
        $user->assignRole('super-admin');

        $user = User::where('id', 2)->first();
        $role = Role::create(['name' => 'Group1']);
        $user->assignRole($role);
        $user->givePermissionTo('moderate_group');

        $user = User::where('id', 3)->first();
        $role = Role::create(['name' => 'Group2']);
        $user->assignRole($role);
        $user->givePermissionTo('moderate_group');

        $role = Role::create(['name' => 'Group3']);
        $role = Role::create(['name' => 'Group4']);
        $role = Role::create(['name' => 'Group5']);
        $role = Role::create(['name' => 'Group6']);

        $role1->givePermissionTo(Permission::all());
    }
}
