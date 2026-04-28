@props([
    'model' => null,
])

<button
    class="submission__imageUploadButton"
    type="button"
    wire:loading.attr="disabled"
>
    <x-icon
        icon="image"
        wire:loading.remove
        wire:target="{{ $model }}"
    />
    <x-icon
        icon="loader-circle"
        wire:loading
        wire:target="{{ $model }}"
    />
    <input
        type="file"
        accept="image/*"
        multiple
        wire:loading.attr="disabled"
        wire:model.live="{{ $model }}"
        wire:target="{{ $model }}"
    />
</button>