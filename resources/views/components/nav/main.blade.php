<ul class="menu">
    <x-nav.menu-item
        :href="route('admin')"
        icon="house"
        :isActive="Route::is('admin')"
        :label="__('nav.dashboard')"
    />
    <x-nav.menu-item
        :href="route('clients')"
        icon="contact"
        :isActive="Route::is('clients') || Route::is('clients.*')"
        :label="__('nav.clients')"
    />
    <x-nav.menu-item
        :href="route('inspection-objects')"
        icon="wrench"
        :isActive="Route::is('inspection-objects') || Route::is('inspection-objects.*')"
        :label="__('nav.inspection_objects')"
    />
    <x-nav.menu-item
        :href="route('inspections')"
        icon="clipboard-check"
        :isActive="Route::is('inspections') || Route::is('inspections.*')"
        :label="__('nav.inspections')"
    />
    <x-nav.menu-item
        :href="route('schemas')"
        icon="list-checks"
        :isActive="Route::is('schemas') || Route::is('schemas.*')"
        :label="__('nav.schemas')"
    />
    {{--
    @can('viewAny', App\Models\Form::class)
        <x-nav.menu-item
            :href="route('forms')"
            icon="text-cursor-input"
            :isActive="Route::is('forms') || Route::is('forms.*')"
            :label="__('nav.forms')"
        />
    @endcan
    @can('viewAny', App\Models\Settings::class)
        <x-nav.menu-item
            :href="route('settings')"
            icon="settings"
            :isActive="Route::is('settings') || Route::is('settings.*')"
            :label="__('nav.settings')"
        />
    @endcan
    --}}
    @can('viewAny', App\Models\User::class)
        <x-nav.menu-item
            :href="route('users')"
            icon="users"
            :isActive="Route::is('users') || Route::is('users.*')"
            :label="__('nav.users')"
        />
    @endcan
</ul>