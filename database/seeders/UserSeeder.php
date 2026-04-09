<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'first_name' => 'Alexander',
            'last_name' => 'Griffioen',
            'email' => 'mail@oscaralexander.com',
            'email_verified_at' => now(),
            'password' => Hash::make('L0d3st@r1981'),
            'role' => UserRole::ADMIN->value,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'first_name' => 'Severin',
            'last_name' => 'Winkler',
            'email' => 'sw@liftinginspections.nl',
            'email_verified_at' => now(),
            'password' => Hash::make('M!isTDbj3AYug_YB'),
            'role' => UserRole::ADMIN->value,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'first_name' => 'Roland',
            'last_name' => 'van Lunteren',
            'email' => 'rvl@liftinginspections.nl',
            'email_verified_at' => now(),
            'password' => Hash::make('4maq.ZkhBH-7.6Nn'),
            'role' => UserRole::ADMIN->value,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'first_name' => 'Rien',
            'last_name' => 'de Jong',
            'email' => 'rdj@liftinginspections.nl',
            'email_verified_at' => now(),
            'password' => Hash::make('AFh69.ypEzrhV6!f'),
            'role' => UserRole::ADMIN->value,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
