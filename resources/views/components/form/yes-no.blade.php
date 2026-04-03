@props([
    'description' => null,
    'id' => null,
    'name' => null,
    'model' => null,
    'required' => false,
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

    $currentValue = isset($__livewire) && $model
        ? data_get($__livewire, $model)
        : null;
@endphp

<div class="field">
    <div class="yesNo"> 
        @if ($text)
            <label
                @class([
                    'yesNo__label',
                    'yesNo__label--required' => $required,
                ])
            >{!! nl2br(Purify::config('label')->clean($text)) !!}</label>
        @endif
        <div class="yesNo__switch">
            <label class="yesNo__option yesNo__option--na">
                <input id="{{ $id }}-na" type="radio" name="{{ $id }}" value="0" wire:model="{{ $model }}" @checked($currentValue !== null && $currentValue == 0) />
                <x-icon icon="circle-slash" />
            </label>
            <label class="yesNo__option yesNo__option--yes">
                <input id="{{ $id }}-yes" type="radio" name="{{ $id }}" value="1" wire:model="{{ $model }}" @checked($currentValue !== null && $currentValue == 1) />
                <x-icon icon="check" />
            </label>
            <label class="yesNo__option yesNo__option--no">
                <input id="{{ $id }}-no" type="radio" name="{{ $id }}" value="-1" wire:model="{{ $model }}" @checked($currentValue !== null && $currentValue == -1) />
                <x-icon icon="x" />
            </label>
        </div>
    </div>
    @error ($model)
        <div class="field__error">{{ $message }}</div>
    @enderror
</div>