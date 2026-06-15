<?php

return [
    'create' => [
        'title' => 'Nieuwe inspectie',
    ],
    'form' => [
        'btn_download_report' => 'Download Keuringsrapport (PDF)',
        'btn_fetch_outsmart' => 'Ophalen uit Outsmart',
        'outsmart' => [
            'toast' => [
                'fetched' => 'Gegevens opgehaald uit Outsmart',
                'not_found' => 'Werkbon niet gevonden in Outsmart',
            ],
        ],
        'crane' => [
            'heading_crane' => 'Kraan',
            'heading_base' => 'Onderstel',
            'heading_boom' => 'Giek',
        ],
        'empty' => [
            'title' => 'Voer de projectgegevens in',
            'description' => 'Voer eerst de projectgegevens in voordat je de inspectie kunt starten.',
        ],
        'heading_crane' => 'Kraan',
        'heading_object' => 'Object',
        'heading_operator_lift' => 'Machinistenlift',
        'heading_project' => 'Project',
        'heading_test_matrix' => 'Beproevingstabel',
        'meta_prompt' => 'Vul een waarde in',
        'status' => [
            'passed' => 'Object is goedgekeurd.',
            'failed' => 'Object is afgekeurd.',
        ],
    ],
    'start' => [
        'modal' => [
            'title' => 'Nieuwe inspectie starten',
            'title_relink' => 'Werkbon koppelen',
            'type' => 'Type',
            'client' => 'Klant',
            'statuses' => 'Filter op status',
            'statuses_placeholder' => 'Alle statussen',
            'work_order' => 'Werkbon',
            'loading_work_orders' => 'Werkbonnen laden…',
            'summary_status' => 'Status',
            'summary_reference' => 'Referentie',
            'summary_work_date' => 'Inspectiedatum',
            'btn_start' => 'Inspectie starten',
            'btn_relink' => 'Werkbon koppelen',
        ],
        'toast' => [
            'relinked' => 'Werkbon gekoppeld',
        ],
    ],
    'project' => [
        'modal' => [
            'title' => 'Projectgegevens',
        ],
        'toast' => [
            'saved' => 'Projectgegevens opgeslagen',
        ],
    ],
    'inspectable' => [
        'modal' => [
            'crane' => [
                'tab_crane' => 'Kraan',
                'tab_base' => 'Onderstel',
                'tab_boom' => 'Giek',
                'title' => 'Kraangegevens',
            ],
            'operator_lift' => [
                'title' => 'Liftgegevens',
            ],
        ],
        'toast' => [
            'saved' => 'Gegevens opgeslagen',
        ],
    ],
    'index' => [
        'col_client' => 'Klant',
        'col_date' => 'Datum',
        'col_project_name' => 'Projectnaam',
        'col_report' => 'Rapport',
        'col_signed' => 'Ondertekend',
        'col_type' => 'Type',
        'btn_create' => 'Nieuwe inspectie',
        'title' => 'Inspecties',
    ],
];
