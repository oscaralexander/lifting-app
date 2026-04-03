@use('Stevebauman\Purify\Facades\Purify')

@props([
    'async' => false,
    'description' => null,
    'label' => null,
    'large' => false,
    'model' => null,
    'name' => null,
    'prefix' => null,
    'row' => false,
    'suffix' => null,
])

@php
    if ($model) {
        $attributes = $attributes->merge(['wire:model' => $model]);
    }

    $model ??= $attributes->whereStartsWith('wire:model')->first();
    $id = $id ?? ($model ? Str::of($model)->slug() : null);
@endphp

<div
    @class([
        'field',
        'field--row' => $row,
    ])
>
    @if ($row)
        <div class="field__col">
    @endif
    @if ($label)
        <label class="field__label" for="{{ $id }}">
            {!! Purify::config('label')->clean($label) !!}
        </label>
    @endif
    @if ($description)
        <div class="field__description">
            {!! Purify::config('description')->clean($description) !!}
        </div>
    @endif
    @if ($row)
        </div>
        <div class="field__col">
    @endif
    <div
        @class([
            'input',
            'input--large' => $large,
            'input--spinner' => $async,
        ])
        @if ($async)
            wire:loading.class="is-loading"
            wire:target="{{ $model }}"
        @endif
    >
        @if ($prefix)
            <div>{!! Purify::config('prefix-suffix')->clean($prefix) !!}</div>
        @endif
        <input
            @error ($model)
                aria-description="{{ $message }}"
                aria-invalid="true"
            @enderror
            id="{{ $id }}"
            {{ $attributes->merge(['type' => 'text']) }}
        >
        @if ($suffix)
            <div>{!! Purify::config('prefix-suffix')->clean($suffix) !!}</div>
        @endif
    </div>
    {{ $slot }}
    @if ($model)
        @error ($model)
            <div class="field__error">{{ $message }}</div>
        @enderror
    @endif
    @if ($row)
        </div>
    @endif
</div>