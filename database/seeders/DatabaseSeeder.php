<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Voucher;
use App\Models\GroupAdmins;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        User::create([
            'name' => 'groupadmin1',
            'email' => 'groupadmin1@gmail.com',
            'password' => Hash::make('groupadmin1'),
        ]);

        User::create([
            'name' => 'groupadmin2',
            'email' => 'groupadmin2@gmail.com',
            'password' => Hash::make('groupadmin2'),
        ]);
        User::create([
            'name' => 'useraccount',
            'email' => 'useraccount@gmail.com',
            'password' => Hash::make('useraccount'),
        ]);
        User::factory(20)->create();
        Voucher::factory(10)->create([
            'users_id'=> 3,
        ]);
        Voucher::factory(10)->create([
            'users_id'=> 4,
        ]);
        Voucher::factory(10)->create([
            'users_id'=> 5,
        ]);
        Voucher::factory(10)->create([
            'users_id'=> 6,
        ]);
    }
}
