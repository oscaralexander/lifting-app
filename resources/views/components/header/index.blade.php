@props([
    'actions' => null,
    'intro' => null,
    'path' => [],
    'title' => null,
])

<header class="admin__header">
    <div class="admin__header-content">
        <ul class="path" role="navigation">
            <li><a href="{{ route('admin') }}" role=wire:navigate>@lang('ui.home')</a></li>
            @foreach ($path as $label => $href)
                <li><a href="{{ $href }}" wire:navigate>{{ $label }}</a></li>
            @endforeach
        </ul>
        <h1>{{ $title }}</h1>
        @if (!empty($intro))
            <p>{{ $intro }}</p>
        @endif
    </div>
    @if ($actions && $actions->hasActualContent())
        <div class="admin__header-actions">
            {{ $actions }}
        </div>
    @endif
</header>