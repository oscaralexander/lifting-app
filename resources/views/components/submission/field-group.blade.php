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

    $requiredFieldKeys = $fieldGroup->fields
        ->filter(fn($f) => $f->type !== FieldType::TOGGLE && $f->pivot->required == 1)
        ->map(fn($f) => [
            'key' => 'field_' . $f->pivot->id,
            'multiple' => $f->type === FieldType::SELECT_MULTIPLE,
        ])
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
        requiredKeys: @js($requiredFieldKeys),
        isRequiredFieldFilled(field) {
            const val = $wire.submissionForm.fields[field.key];

            if (field.multiple) {
                return Array.isArray(val) && val.length > 0;
            }

            return val !== null && val !== undefined && val !== '';
        },
        get allFieldsPassed() {
            if (this.keys.length === 0 && this.requiredKeys.length === 0) return false;

            const togglesPassed = this.keys.every(key => {
                const val = $wire.submissionForm.fields[key];
                return val === 0 || val === 1 || val === '0' || val === '1';
            });

            const requiredFilled = this.requiredKeys.every(field => this.isRequiredFieldFilled(field));

            return togglesPassed && requiredFilled;
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
        <span class="submission__fieldGroupToggleCheck" x-cloak x-show="allFieldsPassed"><x-icon icon="check" /></span>
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
                    <x-submission.field :field="$item['field']" :form="$form" />
                @elseif ($item['type'] === 'formComment')
                    <x-submission.form-comment :form-comment="$item['formComment']" />
                @endif
            @endforeach
        </div>
    </div>
</div>