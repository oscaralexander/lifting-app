@props([
    'initials' => null,
    'size' => null,
    'src' => null,
])

<div
    @class([
        'avatar',
        'avatar--default' => !$src,
        'avatar--large' => ($size === 'large'),
        'avatar--small' => ($size === 'small'),
    ])
>
    @if ($src)
        <img alt="" class="avatar__image" loading="lazy" src="{{ $src }}">
    @else
        <div>{{ $initials }}</div>
    @endif
</div>