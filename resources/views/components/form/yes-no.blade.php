@use('App\Lib\MetaLabel')

@props([
    'description' => null,
    'id' => null,
    'name' => null,
    'model' => null,
    'metaFieldId' => null,
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

    $highlightIndex = $currentValue === null ? 'null' : ($currentValue == 0 ? 0 : ($currentValue == 1 ? 1 : 2));
@endphp

<div class="field">
    <div class="yesNo">
        @if ($text)
            @php
                $renderedLabel = nl2br(Purify::config('label')->clean($text));

                if ($metaFieldId !== null) {
                    $renderedLabel = MetaLabel::render($renderedLabel, $metaFieldId);
                }
            @endphp
            <label
                @class([
                    'yesNo__label',
                    'yesNo__label--required' => $required,
                ])
            >{!! $renderedLabel !!}</label>
        @endif
        <div class="yesNo__switch"
             x-data="{ index: {{ $highlightIndex }} }"
             x-on:change="const v = parseInt($event.target.value); index = v === 0 ? 0 : (v === 1 ? 1 : 2)">
            <div class="yesNo__highlight"
                 x-show="index !== null"
                 x-bind:style="'transform: translateX(calc(' + index + ' * 2rem))'">
            </div>
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
