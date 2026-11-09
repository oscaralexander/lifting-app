<?php

return [
    'create' => [
        'title' => 'Nieuwe inspectie',
    ],
    'form' => [
        'btn_download_appendix' => 'Bijlage',
        'btn_download_report' => 'Keuringsrapport',
        'btn_download_certificate' => 'Certificaat',
        'btn_fetch_outsmart' => 'Ophalen uit Outsmart',
        'btn_generate_report' => 'Keuringsrapport genereren',
        'btn_generate_certificate' => 'Certificaat genereren',
        'select_photo' => 'Foto selecteren',
        'select_photos' => 'Foto\'s selecteren',
        'upload_photo' => 'Foto uploaden',
        'no_photos_heading' => 'Geen foto\'s beschikbaar',
        'no_photos_text' => 'Upload foto’s via de Outsmart app.',
        'no_photos_text_not_linked' => 'Koppel de Outsmart werkbon aan dit project om foto’s te importeren.',
        'outsmart' => [
            'toast' => [
                'document_not_added' => 'Document kon niet aan de werkbon in Outsmart worden toegevoegd',
                'fetched' => 'Gegevens opgehaald uit Outsmart',
                'not_found' => 'Werkbon niet gevonden in Outsmart',
            ],
        ],
        'written_deregistration' => [
            'toast' => [
                'saved' => 'Inspectie goedgekeurd na schriftelijke afmelding',
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
        'heading_attachments' => 'Verwisselbare hulpstukken',
        'heading_crane' => 'Kraan',
        'heading_documents' => 'Documenten',
        'heading_generate_documents' => 'Documenten genereren',
        'heading_object' => 'Object',
        'heading_operator_lift' => 'Machinistenlift',
        'heading_parent_inspection' => 'Hoofdinspectie',
        'heading_project' => 'Project',
        'heading_test_matrix' => 'Beproevingstabel',
        'matrix_add_row' => 'Regel toevoegen',
        'meta_prompt' => 'Vul een waarde in',
        'status' => [
            'passed' => 'Object is goedgekeurd.',
            'failed' => 'Object is afgekeurd.',
        ],
        'text_parent_inspection' => 'Deze inspectie wordt opgenomen in het keuringsrapport van de hoofdinspectie.',
        'text_generate_documents' => 'Genereer de PDF-documenten voor deze inspectie. Documenten worden automatisch toegevoegd aan de werkbon in OutSmart.',
    ],
    'start' => [
        'modal' => [
            'title' => 'Nieuwe inspectie starten',
            'title_relink' => 'Werkbon koppelen',
            'title_relink_parent' => 'Hoofdinspectie koppelen',
            'parent_inspection' => 'Hoofdinspectie',
            'parent_inspection_description' => 'De inspectie van de kraan of machinistenlift waarvan dit hulpstuk deel uitmaakt. Klant, werkbon en projectgegevens worden hiervan overgenomen.',
            'parent_inspection_invalid' => 'Kies een inspectie van een kraan of machinistenlift.',
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
            'btn_relink_parent' => 'Hoofdinspectie koppelen',
        ],
        'toast' => [
            'relinked' => 'Werkbon gekoppeld',
            'parent_relinked' => 'Hoofdinspectie gekoppeld',
        ],
    ],
    'attachment' => [
        'modal' => [
            'already_linked' => 'Dit hulpstuk is al aan deze inspectie gekoppeld.',
            'attachment' => 'Verwisselbaar hulpstuk',
            'btn_add' => 'Hulpstuk toevoegen',
            'form' => 'Keuringsschema',
            'no_attachments' => 'Er zijn nog geen verwisselbare hulpstukken. Maak deze eerst aan bij Inspectieobjecten.',
            'no_forms' => 'Er is nog geen keuringsschema voor verwisselbare hulpstukken. Maak deze eerst aan bij Keuringsschema’s.',
            'title' => 'Verwisselbaar hulpstuk toevoegen',
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
        'col_status' => 'Status',
        'col_type' => 'Type',
        'btn_create' => 'Nieuwe inspectie',
        'filter_status_placeholder' => 'Alle statussen',
        'filter_type_placeholder' => 'Alle types',
        'no_results' => 'Geen inspecties gevonden voor ":query".',
        'search_placeholder' => 'Zoek op projectnaam, klant of werkbonnummer',
        'title' => 'Inspecties',
        'outsmart_link' => 'Outsmart werkbon',
    ],
];
