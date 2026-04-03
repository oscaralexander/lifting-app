@props([
    'active' => false,
    'badge' => null,
    'href' => null,
    'id' => null,
])

<{{ $href ? 'a' : 'button' }}
    @if ($active)
        aria-selected="true"
    @endif
    aria-controls="tabpanel-{{ $id }}"
    class="tabs__tab"
    @if ($href)
        href="{{ $href }}"
    @else
        type="button"
        x-on:click="setActiveTab('{{ $id }}')"
    @endif
    id="tab-{{ $id }}"
    role="tab"
    tabindex="-1"
    @if ($href)
        wire:navigate
    @endif
    x-bind:aria-selected="activeTab === '{{ $id }}'"
    {{ $attributes }}
>
    <span>{{ $slot }}</span>
    @if ($badge)
        <span class="tabs__badge">{{ $badge }}</span>
    @endif
</{{ $href ? 'a' : 'button' }}>
