<?php

return [
    'title' => 'Dashboard',
    'intro' => 'Overzicht van aankomende en recent afgeronde inspecties.',
    'upcoming' => [
        'title' => 'Aankomende inspecties',
        'col_object' => 'Object',
        'col_due' => 'Keuren voor',
        'col_type' => 'Type',
        'days_until' => '{0}Vandaag|{1}:count dag|[2,*]:count dagen',
        'days_overdue' => '{1}:count dag te laat|[2,*]:count dagen te laat',
        'empty' => 'Nog geen objecten met een geplande vervolginspectie.',
        'view_all' => 'Alle objecten',
    ],
    'recent' => [
        'title' => 'Recent afgerond',
        'col_project' => 'Project',
        'col_date' => 'Datum',
        'col_status' => 'Status',
        'empty' => 'Nog geen afgeronde inspecties.',
        'unnamed' => 'Geen naam',
        'view_all' => 'Alle inspecties',
    ],
];
