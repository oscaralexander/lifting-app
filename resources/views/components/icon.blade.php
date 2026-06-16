@props([
    'icon' => null,
    'strokeWidth' => null,
])

<svg
    {{ $attributes->class(['icon', 'lucide']) }}
    @if (! is_null($strokeWidth)) style="--icon-stroke-width: {{ $strokeWidth }}" @endif
    height="24"
    width="24"
    {{ $attributes }}
>
    <use href="{{ asset('assets/img/icons.svg') }}#{{ $icon }}" />
</svg>