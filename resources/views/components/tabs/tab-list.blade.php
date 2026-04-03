@props([
    'flex' => false
])

<div class="tabs__tabScroller">
    <div
        @class([
            'tabs__tabList',
            'tabs__tabList--flex' => $flex,
        ])
    >
        {{ $slot }}
    </div>
</div>