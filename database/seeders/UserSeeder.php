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
            'password' => Hash::make('L0d3st@r1981'),
            'email_verified_at' => now(),
            'role' => UserRole::ADMIN->value,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
