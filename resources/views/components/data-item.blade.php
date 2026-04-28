@props([
    'label',
    'suffix' => null,
    'value',
])

<div class="dataItem">
    <div class="dataItem__label">{{ $label }}</div>
    <div class="dataItem__value">
        {!! Purify::config('value')->clean($value) !!}
        @if ($suffix)
            <span class="dataItem__suffix">{{ $suffix }}</span>
        @endif
    </div>
</div>