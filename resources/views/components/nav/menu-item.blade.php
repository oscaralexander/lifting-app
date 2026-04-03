@props([
    'badge' => null,
    'count' => null,
    'href' => '#',
    'icon' => 'house',
    'isActive' => false,
    'label' => 'Home',
])

<li class="menu__item">
    <a
        @class([
            'menu__link',
            'is-active' => $isActive,
        ])
        href="{{ $href }}"
        wire:navigate
        {{ $attributes }}
    >
        <x-icon :$icon />
        <span class="menu__label">
            {{ $label }}
            @if ($badge)
                <span class="menu__badge">{{ $badge }}</span>
            @endif
            @if ($count)
                <span class="menu__count">{{ $count }}</span>
            @endif
        </span>
    </a>
</li>