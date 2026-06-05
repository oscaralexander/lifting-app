@props([
    'label',
    'suffix' => null,
    'value',
])

@if (!is_null($value) && trim($value) !== '')
    <tr>
        <th scope="row">{{ $label }}</th>
        <td>
            @if (is_numeric($value))
                <span class="dataItem__value">{{ format_number((float) $value) }}</span>
            @else
                <span class="dataItem__value">{!! Purify::config('value')->clean($value) !!}</span>
            @endif
            @if ($suffix)
                <span class="dataItem__suffix">{{ $suffix }}</span>
            @endif
        </td>
    </tr>
@endif