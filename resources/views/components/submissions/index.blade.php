@props([
    'stockItem' => null,
    'submissions' => null,
    'target' => '_self',
])

<div class="submissions">
    @forelse ($submissions as $submission)
        <div class="submissions__item">
            <div class="submissions__itemFlex">
                <a
                    class="submissions__itemLink"
                    href="{{ route('submission.show', ['stickerHash' => $stockItem->sticker->hash, 'hash' => $submission->hash]) }}"
                    target="{{ $target }}"
                >{{ $submission->form->name }}</a>
                <div class="submissions__itemMeta">
                    @lang('qr.show.submission_meta', [
                        'date' => $submission->created_at->isoFormat('D MMM Y (HH:mm)'),
                        'name' => $submission->user->name,
                    ])
                </div>
            </div>
            <div class="submissions__itemActions">
                <x-popout
                    icon="download"
                    id="popout-form-{{ $submission->id }}"
                    position="tl"
                    small
                    transparent
                >
                    <li>
                        <a href="{{ route('submission.pdf', ['stickerHash' => $stockItem->sticker->hash, 'hash' => $submission->hash, 'type' => 'external']) }}">
                            <x-icon icon="file" />
                            @lang('submission.pdf.external')
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('submission.pdf', ['stickerHash' => $stockItem->sticker->hash, 'hash' => $submission->hash, 'type' => 'internal']) }}">
                            <x-icon icon="file" />
                            @lang('submission.pdf.internal')
                        </a>
                    </li>
                </x-popout>
            </div>
        </div>
    @empty
        <p class="submissions__empty">@lang('submission.show.no_submissions')</p>
    @endforelse
</div>