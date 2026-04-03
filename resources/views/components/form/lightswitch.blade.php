@props([
    'description' => null,
    'id' => null,
    'label' => null,
    'name' => null,
    'model' => null,
    'text' => null,
    'value' => 1,
])

@php
    if (!$model && $attributes->whereStartsWith('wire:model')->isNotEmpty()) {
        $model = $attributes->whereStartsWith('wire:model')->first();
    }

    if (!$id) {
        if ($name !== null || $model !== null) {
            $id = Str::camel('input_' . ($name ?? str_replace('.', '_', $model)));
        } else {
            $id = Str::camel('input_' . Str::random());
        }
    }
@endphp

<div class="field field--option">
    @if ($label)
        <label class="field__label" for="{{ $id }}">
            {!! Purify::config('label')->clean($label) !!}
        </label>
        @if ($description)
            <div class="field__description">
                {!! Purify::config('description')->clean($description) !!}
            </div>
        @endif
    @endif
    <div
        @class([
            'lightswitch',
            'lightswitch--noLabel' => empty($text),
        ])
    >
        <input
            @error ($name)
                aria-description="{{ $message }}"
                aria-invalid="true"
            @enderror
            id="{{ $id }}"
            name="{{ $name ?? str_replace('.', '_', $model) }}"
            type="checkbox"
            value="{{ $value }}"
            @if ($model && $attributes->whereStartsWith('wire:model')->isEmpty())
                wire:model="{{ $model }}"
            @endif
            {{ $attributes }}
        >    
        <label for="{{ $id }}">
            <div class="u-stack u-stack-gap-s">
                @if ($text)
                    <div>{!! Purify::config('label')->clean($text) !!}</div>
                @endif
                @if (empty($label) && $description)
                    <div class="field__description">{!! Purify::config('description')->clean($description) !!}</div>
                @endif
            </div>
        </label>
    </div>
    @error ($model)
        <div class="field__error">{{ $message }}</div>
    @enderror
</div>