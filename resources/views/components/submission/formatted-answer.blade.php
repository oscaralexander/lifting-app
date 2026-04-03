@use(\App\Enums\FieldType)

@props([
    'answer' => null,
    'field' => null,
])

<div class="submission__field">
    @if ($field->type === FieldType::TOGGLE)
        <div class="u-flex u-flex-align-center u-flex-gap-m">
            <div class="submission__question u-flex-flex">{{ $field->label }}</div>
            <div
                @class([
                    'submission__yesNo',
                    'submission__yesNo--yes' => $answer == 1,
                    'submission__yesNo--no' => $answer == -1,
                ])
            >
                @if ($answer == 1)
                    <x-icon icon="check" />
                @elseif ($answer == 0)
                    <x-icon icon="minus" />
                @else
                    <x-icon icon="x" />
                @endif
            </div>
        </div>
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
                @elseif ($field->type === FieldType::DOCUMENT || $field->type === FieldType::IMAGE)
                    @php
                        // Normalize legacy string values to arrays
                        $files = is_array($answer) ? $answer : ($answer ? explode(',', $answer) : []);
                    @endphp
                    <div class="submission__files">
                        @foreach ($files as $a)
                            <a
                                class="submission__file"
                                download="{{ basename($a) }}"
                                href="{{ asset('storage/' . $a) }}"
                                data-old-href="{{ route('submission.download', ['path' => $a]) }}"
                            >
                                @php
                                    $icon = in_array(pathinfo($a, PATHINFO_EXTENSION), ['gif', 'jpg', 'jpeg', 'png', 'webp']) ? 'image' : 'file';
                                @endphp
                                <x-icon :icon="$field->type->icon()" />
                                <div class="submission__fileName">{{ basename($a) }}</div>
                            </a>
                        @endforeach
                    </div>
                @else
                    {{ $answer }}
                @endif
            @endif
        </div>
    @endif
</div>