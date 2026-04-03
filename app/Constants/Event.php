<?php

namespace App\Constants;

final readonly class Event
{
    public const DOCUMENT_CREATED = 'document-created';
    public const FIELD_SAVED = 'field-saved';
    public const FORM_CREATED = 'form-created';
    public const FORM_SORTED = 'form-sorted';
    public const FORM_UPDATED = 'form-updated';
    public const REFRESH = 'refresh';
    public const SHOW_SLIDE_OVER = 'show-slide-over';
    public const SORTED_DOCUMENTS = 'sorted-documents';
    public const SORTED_FIELDS = 'sorted-fields';
    public const SORTED_VIDEOS = 'sorted-videos';
    public const STICKER_BATCH_CREATED = 'sticker-batch-created';
    public const TOAST = 'toast';

    public const CLIENT_CREATED = 'client-created';
}
