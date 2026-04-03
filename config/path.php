<?php

return [
    'submissions' => [
        'documents' => env('APP_PATH_SUBMISSIONS_DOCUMENTS', 'submissions/documents'),
        'images' => env('APP_PATH_SUBMISSIONS_IMAGES', 'submissions/images'),
    ],
    'documents' => env('APP_PATH_DOCUMENTS', 'documents'),
    'imports' => env('APP_PATH_IMPORTS', 'imports'),
    'manuals' => env('APP_PATH_MANUALS', 'manuals'),
    'sticker_batches' => env('APP_PATH_STICKER_BATCHES', 'stickers/_batches'),
    'stickers' => env('APP_PATH_STICKERS', 'stickers'),
    'thumbnails' => env('APP_PATH_THUMBNAILS', 'thumbnails'),
];