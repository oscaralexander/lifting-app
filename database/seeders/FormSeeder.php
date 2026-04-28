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
                'created_at' => '2026-03-19 09:23:44',
                'updated_at' => '2026-03-19 09:23:44',
            ],
            [
                'id' => 2,
                'type' => 'operator_lift',
                'name' => 'W3-08 Machinstenlift',
                'description' => 'W3-08/16-138 v6',
                'slug' => 'w3-0816-138-v6',
                'created_at' => '2026-04-09 10:32:44',
                'updated_at' => '2026-04-09 10:34:40',
            ],
        ], ['id'], ['type', 'name', 'description', 'slug']);

        DB::table('field_groups')->upsert([
            // Form 1: W3-01 Torenkraan
            ['id' => 1,  'form_id' => 1, 'name' => 'Algemeen',                                              'number' => '0100', 'position' => 1],
            ['id' => 2,  'form_id' => 1, 'name' => 'Toegangen',                                             'number' => '0200', 'position' => 11],
            ['id' => 3,  'form_id' => 1, 'name' => 'Bedieningsplaatsen',                                    'number' => '0300', 'position' => 18],
            ['id' => 5,  'form_id' => 1, 'name' => 'Lierwerk',                                              'number' => '0700', 'position' => 73],
            ['id' => 6,  'form_id' => 1, 'name' => 'Gieksysteem',                                           'number' => '0400', 'position' => 36],
            ['id' => 7,  'form_id' => 1, 'name' => 'A-Frame / sprenkel',                                    'number' => '0400', 'position' => 44],
            ['id' => 8,  'form_id' => 1, 'name' => 'Loopkat',                                               'number' => '0400', 'position' => 48],
            ['id' => 9,  'form_id' => 1, 'name' => 'Toren- / torenspits',                                   'number' => '0500', 'position' => 57],
            ['id' => 10, 'form_id' => 1, 'name' => 'Zwenkinrichting',                                       'number' => '0600', 'position' => 65],
            ['id' => 11, 'form_id' => 1, 'name' => 'Kabelsystemen',                                         'number' => '0800', 'position' => 82],
            ['id' => 23, 'form_id' => 1, 'name' => 'Hijshaken en - blokken',                                'number' => '0900', 'position' => 92],
            ['id' => 25, 'form_id' => 1, 'name' => 'Opstelling / fundatie',                                 'number' => '1000', 'position' => 102],
            ['id' => 27, 'form_id' => 1, 'name' => 'Afstempeling',                                          'number' => '1100', 'position' => 106],
            ['id' => 28, 'form_id' => 1, 'name' => 'Onder- / bovenwagen',                                   'number' => '1300', 'position' => 109],
            ['id' => 29, 'form_id' => 1, 'name' => 'Railrijwerk / kraanbaan',                               'number' => '1400', 'position' => 112],
            ['id' => 32, 'form_id' => 1, 'name' => 'Elektrische installatie',                               'number' => '1500', 'position' => 126],
            ['id' => 33, 'form_id' => 1, 'name' => 'Hydraulische installatie',                              'number' => '1600', 'position' => 135],
            ['id' => 34, 'form_id' => 1, 'name' => 'Diversen',                                              'number' => '1700', 'position' => 140],
            ['id' => 35, 'form_id' => 1, 'name' => 'Automatische begrenzers, beveiligingen, signaleringen', 'number' => '1800', 'position' => 147],
            ['id' => 36, 'form_id' => 1, 'name' => 'Opschriften',                                           'number' => '1700', 'position' => 142],
            ['id' => 37, 'form_id' => 1, 'name' => 'Bedrijfslastbegrenzer',                                 'number' => '1800', 'position' => 157],
            ['id' => 38, 'form_id' => 1, 'name' => 'Bedrijfslastaanwijzer',                                 'number' => '1800', 'position' => 161],
            ['id' => 39, 'form_id' => 1, 'name' => 'Overige opmerkingen, geen onderdeel van het rapport',   'number' => '2300', 'position' => 167],
            // Form 2: W3-08 Machinistenlift
            ['id' => 12, 'form_id' => 2, 'name' => 'Algemeen',                                              'number' => '0100', 'position' => 1],
            ['id' => 13, 'form_id' => 2, 'name' => 'Toegangen / bordessen',                                 'number' => '0200', 'position' => 7],
            ['id' => 14, 'form_id' => 2, 'name' => 'Bedieningsplaatsen',                                    'number' => '0300', 'position' => 12],
            ['id' => 15, 'form_id' => 2, 'name' => 'Mast / geleiding',                                      'number' => '0400', 'position' => 18],
            ['id' => 16, 'form_id' => 2, 'name' => 'Onder / bovenstation',                                  'number' => '0500', 'position' => 25],
            ['id' => 17, 'form_id' => 2, 'name' => 'Liftkooi',                                              'number' => '0600', 'position' => 30],
            ['id' => 18, 'form_id' => 2, 'name' => 'Elektrische installatie',                               'number' => '0700', 'position' => 42],
            ['id' => 19, 'form_id' => 2, 'name' => 'Drijfwerk / rondsel / tandheugel',                      'number' => '0800', 'position' => 49],
            ['id' => 21, 'form_id' => 2, 'name' => 'Staalkabels',                                           'number' => '0900', 'position' => 56],
            ['id' => 22, 'form_id' => 2, 'name' => 'Opschriften',                                           'number' => '1000', 'position' => 63],
            ['id' => 24, 'form_id' => 2, 'name' => 'Veiligheidsinrichtingen',                               'number' => '1100', 'position' => 69],
            ['id' => 26, 'form_id' => 2, 'name' => 'Beproeving',                                            'number' => '1200', 'position' => 78],
            ['id' => 30, 'form_id' => 2, 'name' => 'Toelichting van de waargenomen tekortkomingen',          'number' => '1300', 'position' => 86],
            ['id' => 31, 'form_id' => 2, 'name' => 'Overige opmerkingen, geen onderdeel van het rapport',   'number' => '1400', 'position' => 89],
        ], ['id'], ['form_id', 'name', 'number', 'position']);
    }
}
