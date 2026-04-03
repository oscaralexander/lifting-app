@props([
    'confirmDelete' => null,
    'video' => null,
])

@use('App\Enums\DocumentType')

<li
    class="file sortableListItem sortable-item"
    data-sortable-id="{{ $video->id }}"
    wire:key="video-{{ $video->id }}"
>
    <div class="sortableListItem__handle sortable-handle"><x-icon icon="grip-vertical" /></div>
    <div class="sortableListItem__flex">
        <div class="u-text-ellipsis">{{ $video->title }}</div>
        <ul class="meta u-text-s u-text-muted">
            <li>{{ $video->created_at->format('j M Y') }}</li>
            @if ($video->videoable_type === 'App\Models\StockItem')
                <li>@lang('videos.single')</li>
            @endif
        </ul>
    </div>
    <div class="sortableListItem__actions">
        <a
            class="sortableListItem__action"
            href="{{ $video->url }}"
            target="_blank"
        ><x-icon icon="youtube" /></a>
        <button
            class="sortableListItem__action"
            type="button"
            wire:click="deleteVideo({{ $video->id }})"
            wire:confirm="{{ $confirmDelete }}"
        ><x-icon icon="trash" /></button>
    </div>
</li>