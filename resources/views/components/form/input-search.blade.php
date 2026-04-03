@props([
    'large' => false,
    'model' => null,
    'name' => null,
])

@php
    $name = $name ?? str_replace('.', '_', $model);
    $id = Str::camel('input_' . $name);
    $model ??= $attributes->whereStartsWith('wire:model')->isNotEmpty()
        ? $attributes->whereStartsWith('wire:model')->first('') : null;
@endphp

<div class="field u-flex-flex">
    <div
        @class([
            'input',
            'input--large' => $large,
            'input--spinner',
            'inputSearch',
        ])
        wire:loading.class="is-loading" wire:target="{{ $model }}"
    >
        <input
            id="{{ $id }}"
            name="{{ $name ?? str_replace('.', '_', $model) }}"
            @if ($model)
                wire:model.live="{{ $model }}"
            @endif
            {{ $attributes->merge(['type' => 'search']) }}
        >
        <i class="inputSearch__icon"></i>
        <button
            class="inputSearch__clear"
            type="button"
            x-on:click.prevent="$wire.set('{{ $model }}', '')"
        ><x-icon icon="x" /></button>
    </div>
</div>