<header class="slideOver__header">
    <h4 class="slideOver__title">{{ $slot }}</h4>
    <button
        aria-label="@lang('ui.close')"
        class="slideOver__close"
        tabindex="0"
        type="button"
        wire:click="$dispatch('hide-slide-over')"
    ><x-icon icon="x" /></button>
</header>