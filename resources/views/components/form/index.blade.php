@props([
    'submit' => 'submit'
])

<form
    {{ $attributes->class(['form']) }}
    wire:loading.class="is-loading"
    wire:submit="{{ $submit }}"
>
    {{ $slot }}
</form>