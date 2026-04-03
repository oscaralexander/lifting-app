@props([
    'block' => false,
    'danger' => false,
    'disabled' => false,
    'dirty' => true,
    'href' => null,
    'icon' => null,
    'iconOnly' => false,
    'mini' => false,
    'navigate' => true,
    'primary' => false,
    'progress' => false,
    'small' => false,
    'submit' => false,
    'text' => false,
    'transparent' => false,
])

@php
    $tag = $href ? 'a' : 'button';
    $iconOnly = $icon && $slot->isEmpty();
@endphp

<{{ $tag }}
    @class([
        'btn',
        'btn--danger' => $danger,
        'btn--icon' => $iconOnly,
        'btn--mini' => $mini,
        'btn--small' => $small,
        'btn--primary' => $primary,
        'btn--progress' => $progress,
        'btn--text' => $text,
        'btn--transparent' => $transparent,
    ])
    @if ($disabled)
        disabled
    @endif
    @if ($href)
        href="{{ $href }}"
        @if ($navigate && $attributes->missing('target'))
            wire:navigate
        @endif
    @else
        @if ($submit)
            type="submit"
            @if ($dirty)
                wire:dirty.class="btn--dirty"
            @endif
        @else
            type="button"
        @endif
    @endif
    {{ $attributes }}
>
    @if ($icon)
        <x-icon :$icon />
    @endif
    @if($slot->isNotEmpty())
        <span>{{ $slot }}</span>
    @endif
</{{ $tag }}>