<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FieldFormSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('field_form')->upsert([
            // Groep 1: Algemeen
            ['id' => 1,  'field_id' => 1,  'field_group_id' => 1, 'form_id' => 1, 'position' => 2,  'required' => 0],
            ['id' => 2,  'field_id' => 2,  'field_group_id' => 1, 'form_id' => 1, 'position' => 3,  'required' => 0],
            ['id' => 3,  'field_id' => 3,  'field_group_id' => 1, 'form_id' => 1, 'position' => 4,  'required' => 0],
            ['id' => 4,  'field_id' => 4,  'field_group_id' => 1, 'form_id' => 1, 'position' => 5,  'required' => 0],
            ['id' => 5,  'field_id' => 5,  'field_group_id' => 1, 'form_id' => 1, 'position' => 6,  'required' => 0],
            ['id' => 6,  'field_id' => 6,  'field_group_id' => 1, 'form_id' => 1, 'position' => 7,  'required' => 0],
            ['id' => 7,  'field_id' => 7,  'field_group_id' => 1, 'form_id' => 1, 'position' => 8,  'required' => 0],
            ['id' => 8,  'field_id' => 8,  'field_group_id' => 1, 'form_id' => 1, 'position' => 9,  'required' => 0],
            // Groep 2: Toegangen
            ['id' => 9,  'field_id' => 9,  'field_group_id' => 2, 'form_id' => 1, 'position' => 11, 'required' => 0],
            ['id' => 10, 'field_id' => 10, 'field_group_id' => 2, 'form_id' => 1, 'position' => 12, 'required' => 0],
            ['id' => 11, 'field_id' => 11, 'field_group_id' => 2, 'form_id' => 1, 'position' => 13, 'required' => 0],
            ['id' => 12, 'field_id' => 12, 'field_group_id' => 2, 'form_id' => 1, 'position' => 14, 'required' => 0],
            ['id' => 13, 'field_id' => 13, 'field_group_id' => 2, 'form_id' => 1, 'position' => 15, 'required' => 0],
            ['id' => 14, 'field_id' => 14, 'field_group_id' => 2, 'form_id' => 1, 'position' => 16, 'required' => 0],
            // Groep 3: Bedieningsplaatsen: Cabine
            ['id' => 15, 'field_id' => 15, 'field_group_id' => 3, 'form_id' => 1, 'position' => 18, 'required' => 0],
            ['id' => 16, 'field_id' => 16, 'field_group_id' => 3, 'form_id' => 1, 'position' => 19, 'required' => 0],
            ['id' => 17, 'field_id' => 17, 'field_group_id' => 3, 'form_id' => 1, 'position' => 20, 'required' => 0],
            ['id' => 18, 'field_id' => 18, 'field_group_id' => 3, 'form_id' => 1, 'position' => 21, 'required' => 0],
            ['id' => 19, 'field_id' => 19, 'field_group_id' => 3, 'form_id' => 1, 'position' => 22, 'required' => 0],
            ['id' => 20, 'field_id' => 20, 'field_group_id' => 3, 'form_id' => 1, 'position' => 23, 'required' => 0],
            ['id' => 21, 'field_id' => 21, 'field_group_id' => 3, 'form_id' => 1, 'position' => 24, 'required' => 0],
            ['id' => 22, 'field_id' => 22, 'field_group_id' => 3, 'form_id' => 1, 'position' => 25, 'required' => 0],
            ['id' => 23, 'field_id' => 23, 'field_group_id' => 3, 'form_id' => 1, 'position' => 26, 'required' => 0],
            ['id' => 24, 'field_id' => 24, 'field_group_id' => 3, 'form_id' => 1, 'position' => 27, 'required' => 0],
            ['id' => 25, 'field_id' => 25, 'field_group_id' => 3, 'form_id' => 1, 'position' => 28, 'required' => 0],
            ['id' => 26, 'field_id' => 26, 'field_group_id' => 3, 'form_id' => 1, 'position' => 29, 'required' => 0],
            ['id' => 27, 'field_id' => 27, 'field_group_id' => 3, 'form_id' => 1, 'position' => 30, 'required' => 0],
            ['id' => 28, 'field_id' => 28, 'field_group_id' => 3, 'form_id' => 1, 'position' => 31, 'required' => 0],
            ['id' => 29, 'field_id' => 29, 'field_group_id' => 3, 'form_id' => 1, 'position' => 32, 'required' => 0],
            ['id' => 30, 'field_id' => 30, 'field_group_id' => 3, 'form_id' => 1, 'position' => 33, 'required' => 0],
            ['id' => 31, 'field_id' => 31, 'field_group_id' => 3, 'form_id' => 1, 'position' => 34, 'required' => 0],
            // Groep 4: Bedieningsplaatsen: Stempelbedieningsplaats
            ['id' => 32, 'field_id' => 15, 'field_group_id' => 4, 'form_id' => 1, 'position' => 36, 'required' => 0],
            ['id' => 33, 'field_id' => 16, 'field_group_id' => 4, 'form_id' => 1, 'position' => 37, 'required' => 0],
            ['id' => 34, 'field_id' => 17, 'field_group_id' => 4, 'form_id' => 1, 'position' => 38, 'required' => 0],
            ['id' => 37, 'field_id' => 20, 'field_group_id' => 4, 'form_id' => 1, 'position' => 39, 'required' => 0],
            ['id' => 38, 'field_id' => 21, 'field_group_id' => 4, 'form_id' => 1, 'position' => 40, 'required' => 0],
            ['id' => 49, 'field_id' => 32, 'field_group_id' => 4, 'form_id' => 1, 'position' => 41, 'required' => 0],
            ['id' => 45, 'field_id' => 28, 'field_group_id' => 4, 'form_id' => 1, 'position' => 42, 'required' => 0],
            // Groep 5: Giek
            ['id' => 50, 'field_id' => 33, 'field_group_id' => 5, 'form_id' => 1, 'position' => 44, 'required' => 0],
        ], ['id'], ['field_id', 'field_group_id', 'form_id', 'position', 'required']);
    }
}
