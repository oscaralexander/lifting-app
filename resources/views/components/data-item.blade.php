@props([
    'label',
    'suffix' => null,
    'value',
])

<div class="dataItem">
    <div class="dataItem__label">{{ $label }}</div>
    <div class="dataItem__value">
        @if (is_numeric($value))
            <span class="dataItem__value">{{ format_number((float) $value) }}</span>
        @else
            <span class="dataItem__value">{!! Purify::config('value')->clean($value) !!}</span>
        @endif
        @if ($suffix)
            <span class="dataItem__suffix">{{ $suffix }}</span>
        @endif
    </div>
</div>