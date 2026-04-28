@props([
    'description' => null,
    'label' => null,
    'model' => null,
    'name' => null,
    'options',
    'placeholder' => '—',
])

@php
    $model ??= $attributes->whereStartsWith('wire:model')->isNotEmpty()
        ? $attributes->whereStartsWith('wire:model')->first('') : null;
    $name = $name ?? Str::of($model)->replace('.', '_');
    $id = Str::camel('input-' . $name);
@endphp

<div class="field">
    @if ($label)
        <label class="field__label" for="{{ $id }}">
            {!! Purify::config('label')->clean($label) !!}
        </label>
    @endif
    @if ($description)
        <div class="field__description">
            {!! Purify::config('descriptions')->clean($description) !!}
        </div>
    @endif
    <div
        class="multiSelect"
        x-data="{
            init() {
                $nextTick(() => {
                    this.updateLabel();
                });
            },
            isOpen: false,
            selection: '',
            selectionCount: 0,
            updateLabel() {
                const $labels = $el.querySelectorAll('input:checked + label');
                const labels = [];

                $labels.forEach(($label) => {
                    labels.push($label.innerText);
                });

                this.selection = labels.join(', ');
                this.selectionCount = `(${labels.length})`;
            }
        }"
        x-on:click="isOpen = true"
        x-on:click.outside="isOpen = false"
    >
        <button class="multiSelect__label" type="button">
            <span class="multiSelect__labelFlex multiSelect__placeholder" x-show="selection === ''">{{ $placeholder }}</span>
            <span class="multiSelect__labelFlex" x-text="selection" x-show="selection !== ''"></span>
            <span class="multiSelect__labelCount" x-text="selectionCount" x-show="selection !== ''"></span>
        </button>
        <div class="multiSelect__dropdown" x-show="isOpen">
            @foreach ($options as $value => $label)
                @php
                    $optionId = $id . '-' . Str::slug($value);
                @endphp
                <div class="multiSelect__option option option--checkbox" title="{{ $label }}">
                    <input
                        id="{{ $optionId }}"
                        type="checkbox"
                        value="{{ $value }}"
                        @if ($model && $attributes->whereStartsWith('wire:model')->isEmpty())
                            wire:model="{{ $model }}"
                        @else
                            {{ $attributes->whereStartsWith('wire:model') }}
                        @endif
                        x-on:click="updateLabel"
                    >
                    <label for="{{ $optionId }}"><span>{{ $label }}</span></label>
                </div>
            @endforeach
        </div>
    </div>
</div>