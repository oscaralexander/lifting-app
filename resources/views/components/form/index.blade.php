@props([
    'submit' => 'submit'
])

<form
    wire:loading.class="is-loading"
    wire:submit="{{ $submit }}"
    {{ $attributes }}
>
    {{ $slot }}
</form>