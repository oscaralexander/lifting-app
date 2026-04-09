@use(App\Enums\Locale)
@use(App\Enums\UserRole)

@push('pageTitle')
    {{ __('users.create.title') }}
@endpush

<div>
    <header class="admin__header">
        <ul class="path">
            <li><a href="{{ route('admin') }}" wire:navigate>@lang('ui.home')</a></li>
            <li><a href="{{ route('admin.users') }}" wire:navigate>@lang('users.index.title')</a></li>
        </ul>
        <h1>@lang('users.create.title')</h1>
    </header>
    <div class="u-stack u-stack-gap-l">
        @include('livewire.admin.users._form')
    </div>
</div>