@props(['document'])

<li class="document">
    @if ($thumbnailUrl = $document->thumbnailUrl)
        <div class="document__thumb">
            <img alt="{{ $document->filename_orig }}" src="{{ $thumbnailUrl }}">
            <div class="document__actions">
                <a class="document__action" href="{{ $document->url . ($document->isPublic ? '' : '&view=1') }}" target="_blank"><x-icon icon="eye" /></a>
                <a class="document__action" href="{{ $document->url }}" download="{{ $document->filename_orig }}"><x-icon icon="download" /></a>
            </div>
        </div>
    @endif
    <div class="document__info">
        <a
            class="document__link"
            download="{{ $document->filename_orig }}"
            href="{{ $document->url }}"
        >{{ pathinfo($document->filename_orig, PATHINFO_FILENAME) }}</a>
        <ul class="document__meta meta">
            <li>{{ $document->filesize }}</li>
            <li><time datetime="{{ $document->created_at->format('Y-m-d') }}">{{ $document->created_at->isoFormat('D MMMM Y') }}</time></li>
        </ul>
    </div>
    <!-- {{ $document->path }} -->
</li>
