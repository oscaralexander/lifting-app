@props([
    'label' => null,
    'model' => null,
    'name' => null,
    'type' => 'checkbox', // checkbox|radio
    'value' => 1,
])

@php
    $name = $name ?? str_replace('.', '_', $model);
    $id = Str::camel('input_' . $name);
    $model ??= $attributes->whereStartsWith('wire:model')->isNotEmpty()
        ? $attributes->whereStartsWith('wire:model')->first('') : null;
@endphp

<div
    @class([
        'option',
        'option--checkbox' => $type === 'checkbox',
        'option--radio' => $type === 'radio',
    ])
>
    <input
        id="{{ $name ?? str_replace('.', '_', $model) }}-{{ Str::slug($value) }}"
        @if ($model)
            wire:model="{{ $model }}"
        @endif
        name="{{ $name ?? str_replace('.', '_', $model) }}[]"
        type="{{ $type === 'checkbox' ? 'checkbox' : 'radio' }}"
        value="{{ $value }}"
        {{ $attributes }}
    >
    <label for="{{ $name ?? str_replace('.', '_', $model) }}-{{ Str::slug($value) }}">{!! $label !!}</label>
</div>