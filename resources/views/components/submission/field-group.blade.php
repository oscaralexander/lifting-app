@use('App\Enums\FieldType')

@props([
    'fieldGroup' => null,
    'form' => null,
    'submission' => null,
])

@php
    $toggleFieldKeys = $fieldGroup->fields
        ->filter(fn($f) => $f->type === FieldType::TOGGLE)
        ->map(fn($f) => 'field_' . $f->pivot->id)
        ->values()
        ->toArray();

    // Collect all fields and comments, order by position
    $items = collect();

    foreach ($fieldGroup->fields->sortBy('pivot.position') as $field) {
        $items->push([
            'type' => 'field',
            'field' => $field,
            'position' => $field->pivot->position ?? 0,
        ]);
    }

    foreach ($fieldGroup->formComments->sortBy('position') as $formComment) {
        $items->push([
            'type' => 'formComment',
            'formComment' => $formComment,
            'position' => $formComment->position ?? 0,
        ]);
    }

    // Order all items by position
    $items = $items->sortBy('position');
@endphp

<div
    class="submission__fieldGroup"
    x-bind:class="{ 'is-expanded': isExpanded }"
    x-data="{
        isExpanded: false,
        keys: @js($toggleFieldKeys),
        get allTogglesPassed() {
            if (this.keys.length === 0) return false;

            return this.keys.every(key => {
                const val = $wire.submissionForm.fields[key];
                return val === 0 || val === 1 || val === '0' || val === '1';
            });
        },
        get hasFailures() {
            if (this.keys.length === 0) return false;

            return this.keys.some(key => {
                const val = $wire.submissionForm.fields[key];
                return val === -1 || val === '-1';
            });
        }
    }"
>
    <button
        aria-controls="fieldGroup-{{ $fieldGroup->id }}"
        aria-expanded="false"
        class="submission__fieldGroupToggle"
        type="button"
        x-bind:aria-expanded="isExpanded"
        x-on:click="isExpanded = !isExpanded"
    >
        <span class="submission__fieldGroupToggleName">{!! $fieldGroup->numberedName !!}</span>
        <span class="submission__fieldGroupToggleError"><x-icon icon="triangle-alert" /></span>
        <span class="submission__fieldGroupToggleCheck" x-cloak x-show="allTogglesPassed"><x-icon icon="check" /></span>
        <span class="submission__fieldGroupToggleError" x-cloak x-show="hasFailures"><x-icon icon="x" /></span>
        <span class="submission__fieldGroupToggleIcon"></span>
    </button>
    <div
        class="submission__fieldGroupFields"
        id="fieldGroup-{{ $fieldGroup->id }}"
    >   
        <div class="submission__box">
            @foreach ($items as $item)
                @if ($item['type'] === 'field')
                    @if ($submission)
                        <x-submission.formatted-answer
                            :answer="$submission->getAnswerForField($item['field']->pivot->id)"
                            :field="$item['field']"
                        />
                    @else
                        <x-submission.field :field="$item['field']" :form="$form" />
                    @endif
                @elseif ($item['type'] === 'formComment')
                    <x-submission.form-comment :form-comment="$item['formComment']" />
                @endif
            @endforeach
        </div>
    </div>
</div>