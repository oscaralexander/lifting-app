@push('pageTitle')
    {{ $user->name }}
@endpush

<div>
    <header class="admin__header">
        <ul class="path">
            <li><a href="{{ route('admin') }}" wire:navigate>@lang('ui.home')</a></li>
            @can('viewAny', App\Models\User::class)
                <li><a href="{{ route('admin.users') }}" wire:navigate>@lang('users.index.title')</a></li>
            @endcan
        </ul>
        <h1>{{ $user->name }}</h1>
    </header>
    <div class="u-stack u-stack-gap-l">
        @include('livewire.admin.users._form')
    </div>
</div>