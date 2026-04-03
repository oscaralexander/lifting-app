<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SubmissionFormSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            FormSeeder::class,
            FieldSeeder::class,
            FieldFormSeeder::class,
        ]);
    }
}
