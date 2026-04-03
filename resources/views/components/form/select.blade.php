@props([
    'default' => null,
    'description' => null,
    'label' => null,
    'model' => null,
    'name' => null,
    'options' => [],
    'selected' => null,
])

@php
    if ($model) {
        $attributes = $attributes->merge(['wire:model' => $model]);
    }

    $model ??= $attributes->whereStartsWith('wire:model')->first();
    $id = $id ?? ($model ? Str::of($model)->slug() : null);
@endphp

<div class="field">
    @if ($label)
    <div class="field__labelAction">
        <label class="field__label" for="{{ $id }}">
            {!! Purify::config('label')->clean($label) !!}
        </label>
        @if (isset($fieldAction))
            {{ $fieldAction }}
        @endif
    </div>
    @endif
    @if ($description)
        <div class="field__description">{!! $description !!}</div>
    @endif
    <select
        @error ($name)
            aria-description="{{ $message }}"
            aria-invalid="true"
        @enderror
        class="select"
        id="{{ $id }}"
        {{ $attributes }}
    >
        @if ($default)
            <option value="">{{ $default }}</option>
        @endif
        @foreach ($options as $value => $label)
            <option value="{{ $value }}" @selected($selected !== null && (string) $value === (string) $selected)>{{ $label }}</option>
        @endforeach
    </select>
    @if ($model)
        @error ($model)
            <div class="field__error">{{ $message }}</div>
        @enderror
    @endif
</div>