<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('forms')->upsert([
            [
                'id' => 1,
                'type' => 'crane',
                'name' => 'W3-01 Torenkraan',
                'description' => 'TCVT W3-01: 2025 (24-V11)',
                'slug' => 'w3-01-torenkraan',
                'created_at' => '2026-03-19 10:23:44',
                'updated_at' => '2026-03-19 10:23:44',
            ],
        ], ['id'], ['type', 'name', 'description', 'slug']);

        DB::table('field_groups')->upsert([
            ['id' => 1, 'form_id' => 1, 'name' => 'Algemeen', 'number' => '0100', 'position' => 1],
            ['id' => 2, 'form_id' => 1, 'name' => 'Toegangen', 'number' => '0200', 'position' => 10],
            ['id' => 3, 'form_id' => 1, 'name' => 'Bedieningsplaatsen: Cabine', 'number' => '0300', 'position' => 17],
            ['id' => 4, 'form_id' => 1, 'name' => 'Bedieningsplaatsen: Stempelbedieningsplaats', 'number' => '0300', 'position' => 35],
            ['id' => 5, 'form_id' => 1, 'name' => 'Giek', 'number' => '0700', 'position' => 43],
        ], ['id'], ['form_id', 'name', 'number', 'position']);
    }
}
