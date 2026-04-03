@props([
    'activeTab' => null,
    'alt' => false,
    'id' => null,
])

<div
    @class([
        'tabs',
        'tabs--alt' => $alt,
    ])
    x-data="tabs('{{ $id }}', {{ $activeTab ? "'" . $activeTab . "'" : 'null' }})"
    {{ $attributes }}
>
    {{ $slot }}
</div>
