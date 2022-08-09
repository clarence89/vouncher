<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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

        Permission::create(['name' => 'moderate group']);


        // create roles and assign created permissions



        // or may be done by chaining
        $role = Role::create(['name' => 'Group1']);
 //           ->givePermissionTo(['publish articles', 'unpublish articles']);
        $role = Role::create(['name' => 'Group2']);

        $role = Role::create(['name' => 'Group3']);

        $role = Role::create(['name' => 'Group4']);



        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());
    }
}
