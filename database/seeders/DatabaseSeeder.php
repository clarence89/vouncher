<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\GroupAdmins;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('adminadmin'),
        ]);
        $admin = User::create([
            'name' => 'groupadmin1',
            'email' => 'groupadmin1@gmail.com',
            'password' => Hash::make('groupadmin1'),
        ]);

        $admin = User::create([
            'name' => 'groupadmin2',
            'email' => 'groupadmin2@gmail.com',
            'password' => Hash::make('groupadmin2'),
        ]);
        User::factory(10)->create();
    }
}
