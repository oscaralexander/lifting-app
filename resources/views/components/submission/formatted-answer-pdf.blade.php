@use(\App\Enums\FieldType)

@props([
    'answer' => null,
    'field' => null,
])

<div class="submission__field">
    @if ($field->type === FieldType::TOGGLE)
        <table width="100%">
            <tr>
                <td class="submission__question" valign="middle">{{ $field->label }}</td>
                <td align="right" valign="middle">
                    <img alt="✓" height="24" src="{{ public_path() . '/assets/img/icons/' . $answer . '.svg' }}" width="24" />
                    {{-- @if ($answer == 1)
                        <img alt="✓" height="24" src="{{ public_path() . '/assets/img/icons/check.svg' }}" width="24" />
                    @endif --}}
                </td>
            </tr>
        </table>
    @else
        <div class="submission__question">{{ $field->label }}</div>
        <div class="submission__answer">
            @if ($answer === null)
                <span class="u-text-lc" style="opacity: .5;">—</span>
            @else
                @if ($field->type === FieldType::SELECT_MULTIPLE)
                    @php
                        $selected = array_keys(array_filter($answer));
                        $selectedValues = [];

                        foreach (explode("\n", $field->values) as $i => $value) {
                            if (in_array($i, $selected)) {
                                $selectedValues[] = $value;
                            }
                        }
                    @endphp
                    {{ Arr::join($selectedValues, ', ') }}
                @elseif ($field->type === FieldType::SELECT)
                    {{ $field->options[$answer] }}                   
                @elseif ($field->type === FieldType::IMAGE)
                    @php
                        // Normalize legacy string values to arrays
                        $images = is_array($answer) ? $answer : ($answer ? explode(',', $answer) : []);
                    @endphp
                    @foreach ($images as $image)
                        <img alt="{{ basename($image) }}" src="{{ storage_path() . '/app/public/' . $image }}" />
                    @endforeach
                @elseif ($field->type === FieldType::DOCUMENT)
                    @php
                        // Normalize legacy string values to arrays
                        $documents = is_array($answer) ? $answer : ($answer ? explode(',', $answer) : []);
                    @endphp
                    @foreach ($documents as $document)
                        
                    @endforeach
                @else
                    {{ $answer }}
                @endif
            @endif
        </div>
    @endif
</div>