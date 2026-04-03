<div
    class="tabs__tabPanel"
    id="tabpanel-{{ $id }}"
    role="tabpanel"
    tabindex="-1"
    x-bind:hidden="activeTab !== '{{ $id }}'"
>
    {{ $slot }}
</div>
