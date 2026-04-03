@props([
    'fieldGroup' => null,
    'submission' => null,
])

@php
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

<div class="submission__fieldGroup">
    <div class="submission__fieldGroupName">{{ $fieldGroup->name }}</div>
    <div
        class="submission__fieldGroupFields"
        id="fieldGroup-{{ $fieldGroup->id }}"
    >
        @foreach ($items as $item)
            @switch ($item['type'])
                @case('field')
                    <x-submission.formatted-answer-pdf
                        :answer="$submission->getAnswerForField($item['field']->pivot->id)"
                        :field="$item['field']"
                    />
                    @break
                @case('formComment')
                    <x-submission.form-comment :form-comment="$item['formComment']" />
                    @break
            @endswitch
        @endforeach
    </div>
</div>