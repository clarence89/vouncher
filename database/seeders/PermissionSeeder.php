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

        // create permissions

        Permission::create(['name' => 'moderate_group']);


        // create roles and assign created permissions


        $role1 = Role::create(['name' => 'super-admin']);

        // or may be done by chaining

 //           ->givePermissionTo(['publish articles', 'unpublish articles']);






        $user = User::where('id', 1)->first();
        $user->assignRole('super-admin');

        $user = User::where('id', 2)->first();
        $role = Role::create(['name' => 'Group1']);
        $user->assignRole('Group1');
        $user->givePermissionTo('moderate_group');
        GroupAdmins::create([
            'user_id'=> $user->id,
            'role_id' => $role->id,
        ]);

        $user = User::where('id', 3)->first();
        $role = Role::create(['name' => 'Group2']);
        $user->assignRole('Group2');
        $user->givePermissionTo('moderate_group');
        GroupAdmins::create([
            'user_id'=> $user->id,
            'role_id' => $role->id,
        ]);

        $role = Role::create(['name' => 'Group3']);

        $role = Role::create(['name' => 'Group4']);

        $role1->givePermissionTo(Permission::all());
    }
}
