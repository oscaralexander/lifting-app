@props([
    'confirmDelete' => null,
    'document' => null,
])

@use('App\Enums\DocumentType')

<li
    class="file sortableListItem sortable-item"
    data-root="{{ env('APP_PATH_MANUALS') }}"
    data-sortable-id="{{ $document->id }}"
    data-path="{{ $document->path }}"
    data-path-thumbnail="{{ $document->thumbnailPath }}"
    wire:key="document-{{ $document->id }}"
>
    <div class="sortableListItem__handle sortable-handle"><x-icon icon="grip-vertical" /></div>
    <div class="sortableListItem__flex">
        <div class="file__filename">
            @php
                $baseName = pathinfo($document->filename_orig, PATHINFO_FILENAME);
                $extension = pathinfo($document->filename_orig, PATHINFO_EXTENSION);
            @endphp
            <span class="file__baseName">{{ $baseName }}</span>
            <span class="file__extension">{{ $extension }}</span>
        </div>
        <ul class="meta u-text-s u-text-muted">
            <li>{{ $document->created_at->format('j M Y') }}</li>
            @if ($document->type === DocumentType::MANUAL)
                @if ($document->documentable_type === 'App\Models\StockItem')
                    <li>@lang('documents.single')</li>
                @endif
            @endif
        </ul>
    </div>
    <div class="sortableListItem__actions">
        <a
            class="sortableListItem__action"
            href="{{ $document->url . ($document->isPublic ? '' : '&view=1') }}"
            target="_blank"
        ><x-icon icon="eye" /></a>
        <a
            class="sortableListItem__action"
            @if ($document->type->isPublic())
                download="{{ $document->filename_orig }}"
            @endif
            href="{{ $document->url }}"
        ><x-icon icon="download" /></a>
        <button
            class="sortableListItem__action"
            type="button"
            wire:click="deleteDocument({{ $document->id }})"
            wire:confirm="{{ $confirmDelete }}"
        ><x-icon icon="trash" /></button>
    </div>
    <!-- {{ $document->path }} -->
</li>