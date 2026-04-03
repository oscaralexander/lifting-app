@props([
    'description' => null,
    'label' => null,
    'model' => null,
    'name' => null,
    'options' => [], // ['value' => 'label']
    'required' => false,
    'type' => 'checkbox', // checkbox|radio
    'vertical' => false,
])

<div class="field">
    @if ($label)
        <label
            @class([
                'field__label',
                'field__label--required' => $required,
            ])
        >{{ $label }}</label>
    @endif
    @if ($description)
        <div class="field__description">{{ $description }}</div>
    @endif
    <div class="u-stack u-stack-gap-xs">
        @foreach ($options as $value => $label)
            @php
                $name = str_replace('.', '_', $model) . '_' . $value;
            @endphp
            <x-form.option
                :$label
                model="{{ $model }}.{{ $value }}"
                :$name
                :$type
                :$value
            />
        @endforeach
    </div>
    @if ($model)
        @error ($model)
            <div class="field__error">{{ $message }}</div>
        @enderror
    @endif
</div>
