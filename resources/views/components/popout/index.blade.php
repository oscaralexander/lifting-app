@props([
    'icon' => 'ellipsis-vertical',
    'id' => null,
    'label' => null,
    'primary' => false,
    'position' => 'tc',
    'small' => false,
    'transparent' => false,
])

<button
    aria-controls="{{ $id }}"
    aria-expanded="false"
    @class([
        'popout',
        'popout--icon' => $icon && !$label,
        'popout--primary' => $primary,
        'popout--small' => $small,
        'popout--transparent' => $transparent,
    ])
    type="button"
    x-bind:aria-expanded="isOpen"
    x-data="popout"
    x-on:click.outside="isOpen = false"
>
    @if ($icon)
        <x-icon :$icon />
    @endif
    {{ $label }}
    <ul class="popout__menu" id="{{ $id }}" x-ref="menu">
        {{ $slot }}
    </ul>
</button>