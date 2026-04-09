<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FieldSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('fields')->upsert([
            ['id' => 1,  'number' => '0101', 'type' => 'toggle', 'label' => "Volgende documenten aanwezig:\n• Kraanboek\n• Hijstabellen\n• Gebruiksaanwijzing\n• EG-verklaring van overeenstemming\n• Specificaties staalkabels\n• Specificaties hijshaken/ -blokken\n• Fundatieberekening\n• Reparatierapport\n• Service alerts", 'description' => null, 'values' => null, 'attrs' => '[]'],
            ['id' => 2,  'number' => '0102', 'type' => 'toggle', 'label' => 'Opbouwvolgorde',                                                                          'description' => null, 'values' => null, 'attrs' => '[]'],
            ['id' => 3,  'number' => '0103', 'type' => 'toggle', 'label' => 'Configuratie van de hijskraan overeenkomstig specificaties fabrikant en aanwezige documentatie', 'description' => null, 'values' => null, 'attrs' => '[]'],
            ['id' => 4,  'number' => '0104', 'type' => 'toggle', 'label' => 'Machinistenlift passend bij configuratie hijskraan',                                       'description' => null, 'values' => null, 'attrs' => '[]'],
            ['id' => 5,  'number' => '0105', 'type' => 'toggle', 'label' => 'Zijn ingrijpende wijzigingen/reparaties/deskundig uitgevoerd',                              'description' => null, 'values' => null, 'attrs' => '[]'],
            ['id' => 6,  'number' => '0106', 'type' => 'toggle', 'label' => 'Zijn funderingsberekeningen uitgevoerd',                                                   'description' => null, 'values' => null, 'attrs' => '[]'],
            ['id' => 7,  'number' => '0107', 'type' => 'toggle', 'label' => 'Staalkabels overeenkomstig specificaties fabrikant',                                       'description' => null, 'values' => null, 'attrs' => '[]'],
            ['id' => 8,  'number' => '0108', 'type' => 'toggle', 'label' => 'Onderzoek staande (tui)kabels uitgevoerd',                                                 'description' => null, 'values' => null, 'attrs' => '[]'],
            ['id' => 9,  'number' => '0201', 'type' => 'toggle', 'label' => 'Opstappen',                                                                                'description' => null, 'values' => null, 'attrs' => '[]'],
            ['id' => 10, 'number' => '0202', 'type' => 'toggle', 'label' => 'Bordessen/loopvlakken',                                                                    'description' => null, 'values' => null, 'attrs' => '[]'],
            ['id' => 11, 'number' => '0203', 'type' => 'toggle', 'label' => 'Handgrepen',                                                                               'description' => null, 'values' => null, 'attrs' => '[]'],
            ['id' => 12, 'number' => '0204', 'type' => 'toggle', 'label' => 'Ladders/klimkooien',                                                                       'description' => null, 'values' => null, 'attrs' => '[]'],
            ['id' => 13, 'number' => '0205', 'type' => 'toggle', 'label' => 'Leeflijnen',                                                                               'description' => null, 'values' => null, 'attrs' => '[]'],
            ['id' => 14, 'number' => '0206', 'type' => 'toggle', 'label' => 'Toegangshek',                                                                              'description' => null, 'values' => null, 'attrs' => '[]'],
            ['id' => 15, 'number' => '0301', 'type' => 'toggle', 'label' => 'Bediening (knoppen / hendels / pedalen / aanduiding)',                                     'description' => null, 'values' => null, 'attrs' => '[]'],
            ['id' => 16, 'number' => '0302', 'type' => 'toggle', 'label' => 'Instrumenten',                                                                             'description' => null, 'values' => null, 'attrs' => '[]'],
            ['id' => 17, 'number' => '0303', 'type' => 'toggle', 'label' => 'Hijstabellen / torenbord',                                                                 'description' => null, 'values' => null, 'attrs' => '[]'],
            ['id' => 18, 'number' => '0304', 'type' => 'toggle', 'label' => 'Last- en vluchtaanduiding / giekborden',                                                  'description' => null, 'values' => null, 'attrs' => '[]'],
            ['id' => 19, 'number' => '0305', 'type' => 'toggle', 'label' => 'Contactslot',                                                                              'description' => null, 'values' => null, 'attrs' => '[]'],
            ['id' => 20, 'number' => '0306', 'type' => 'toggle', 'label' => 'Noodstop(pen)',                                                                            'description' => null, 'values' => null, 'attrs' => '[]'],
            ['id' => 21, 'number' => '0307', 'type' => 'toggle', 'label' => 'Aanstootbeveiliging hendels',                                                              'description' => null, 'values' => null, 'attrs' => '[]'],
            ['id' => 22, 'number' => '0308', 'type' => 'toggle', 'label' => 'Claxon',                                                                                   'description' => null, 'values' => null, 'attrs' => '[]'],
            ['id' => 23, 'number' => '0309', 'type' => 'toggle', 'label' => 'Ruitenwissers',                                                                            'description' => null, 'values' => null, 'attrs' => '[]'],
            ['id' => 24, 'number' => '0311', 'type' => 'toggle', 'label' => 'Toegangsdeuren / -luik',                                                                  'description' => null, 'values' => null, 'attrs' => '[]'],
            ['id' => 25, 'number' => '0312', 'type' => 'toggle', 'label' => 'Ruiten',                                                                                   'description' => null, 'values' => null, 'attrs' => '[]'],
            ['id' => 26, 'number' => '0314', 'type' => 'toggle', 'label' => 'Zitplaats / gordel',                                                                       'description' => null, 'values' => null, 'attrs' => '[]'],
            ['id' => 27, 'number' => '0315', 'type' => 'toggle', 'label' => 'Beveiliging machinist',                                                                    'description' => null, 'values' => null, 'attrs' => '[]'],
            ['id' => 28, 'number' => '0316', 'type' => 'toggle', 'label' => 'Verwarming / ventilatie / verlichting',                                                    'description' => null, 'values' => null, 'attrs' => '[]'],
            ['id' => 29, 'number' => '0317', 'type' => 'toggle', 'label' => 'Zonneklep / zonwering',                                                                    'description' => null, 'values' => null, 'attrs' => '[]'],
            ['id' => 30, 'number' => '0318', 'type' => 'toggle', 'label' => 'Cabine',                                                                                   'description' => null, 'values' => null, 'attrs' => '[]'],
            ['id' => 31, 'number' => '0319', 'type' => 'toggle', 'label' => 'Noodvoorziening',                                                                          'description' => null, 'values' => null, 'attrs' => '[]'],
            ['id' => 32, 'number' => '0313', 'type' => 'toggle', 'label' => 'Controle horizontaal stand',                                                               'description' => null, 'values' => null, 'attrs' => '[]'],
            ['id' => 33, 'number' => '0709', 'type' => 'toggle', 'label' => 'Test',                                                                                     'description' => null, 'values' => null, 'attrs' => '[]'],
        ], ['id'], ['number', 'type', 'label', 'description', 'values', 'attrs']);
    }
}
