<?php

return [
    'form' => [
        'errors' => [
            'role' => 'De geselecteerde rol is ongeldig.',
        ],
    ],
    'index' => [
        'btn_create' => 'Nieuwe gebruiker',
        'col_name' => 'Naam',
        'col_email' => 'E-mailadres',
        'col_last_seen_at' => 'Laatst actief',
        'col_role' => 'Rol',
        'col_status' => 'Status',
        'delete_confirm' => 'Weet u zeker dat u deze gebruiker wilt verwijderen?',
        'popout_resend_invite' => 'Uitnodiging opnieuw versturen',
        'title' => 'Gebruikers',
    ],
    'create' => [
        'errors' => [
            'email_unique' => 'Er bestaat reeds een gebruiker met dit e-mailadres.',
            'role_enum' => 'De geselecteerde rol is ongeldig.',
        ],
        'title' => 'Nieuwe gebruiker',
    ],
    'toast' => [
        'created' => 'Gebruiker aangemaakt',
        'updated' => 'Gebruiker aangepast',
        'deleted' => 'Gebruiker verwijderd',
        'invite_sent' => 'Uitnodiging verstuurd',
    ],
];
