@props([
    'description' => null,
    'label' => null,
    'model' => null,
    'name' => null,
    'rows' => 2,
])

<div
    {{ $attributes->class([
        'field',
        'has-error' => $errors->has($model),
    ]) }}
>
    @if ($label)
        <label class="field__label" for="textarea_{{ $name ?? str_replace('.', '_', $model) }}">{{ $label }}</label>
    @endif
    @if ($description)
        <div class="field__description">{{ $description }}</div>
    @endif
    <div class="textarea" x-bind:data-replicated-value="$wire.{{ $model }}">
        <textarea
            @error ($model)
                aria-invalid="true"
                aria-description="{{ $message }}"
            @enderror
            id="textarea_{{ $name ?? str_replace('.', '_', $model) }}"
            name="{{ $name ?? str_replace('.', '_', $model) }}"
            rows="{{ $rows }}"
            @if ($model)
                wire:model="{{ $model }}"
            @endif
            {{ $attributes->whereDoesntStartWith('class') }}
        ></textarea>
    </div>
    @error ($model)
        <div class="field__error">{{ $message }}</div>
    @enderror
</div>