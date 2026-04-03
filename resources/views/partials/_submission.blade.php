@use(\App\Enums\FieldType)

{{--
@php
    // Collect all fields and groups, order by position
    $items = collect();

    // Add groups with their fields
    foreach ($this->submission->form->fieldGroups->sortBy('pivot.position') as $group) {
        $items->push([
            'type' => 'group',
            'group' => $group,
            'position' => $group->pivot->position ?? $group->position ?? 0,
        ]);
    }

    // Add fields not in any group
    $groupedFieldIds = $this->submission->form->fieldGroups->flatMap(function($group) {
        return $group->fields->pluck('id');
    })->unique();

    foreach ($this->submission->form->fields->sortBy('pivot.position') as $field) {
        if (!$groupedFieldIds->contains($field->id)) {
            $items->push([
                'type' => 'field',
                'field' => $field,
                'position' => $field->pivot->position ?? $field->position ?? 0,
            ]);
        }
    }

    // Order all items by position
    $items = $items->sortBy('position');
@endphp
--}}

@php
    $form = $this->submission->form;
    $items = collect();

    // Add field groups
    foreach ($form->fieldGroups->sortBy('position') as $fieldGroup) {
        $items->push([
            'fieldGroup' => $fieldGroup,
            'position' => $fieldGroup->position ?? 0,
            'type' => 'fieldGroup',
        ]);
    }

    // Add comments not in any group
    $formComments = $form->formComments->filter(function($formComment) {
        return is_null($formComment->field_group_id);
    })->sortBy('position');

    foreach ($formComments->sortBy('position') as $formComment) {
        $items->push([
            'formComment' => $formComment,
            'position' => $formComment->position ?? 0,
            'type' => 'formComment',
        ]);
    }

    // Add fields not in any group
    $groupedFieldPivotIds = $form->fieldGroups->flatMap(function($fieldGroup) {
        return $fieldGroup->fields->pluck('pivot.id');
    })->unique();

    foreach ($form->fields->sortBy('pivot.position') as $field) {
        if (!$groupedFieldPivotIds->contains($field->pivot->id)) {
            $items->push([
                'field' => $field,
                'position' => $field->pivot->position ?? $field->position ?? 0,
                'type' => 'field',
            ]);
        }
    }

    // Order all items by position
    $items = $items->sortBy('position');
@endphp

<div class="submission">
    @foreach ($items as $item)
        @switch($item['type'])
            @case('fieldGroup')
                <x-submission.field-group
                    :field-group="$item['fieldGroup']"
                    :submission="$this->submission"
                />
                @break

            @case('field')
                <x-submission.formatted-answer
                    :answer="$this->submission->getAnswerForField($item['field']->pivot->id)"
                    :field="$item['field']"
                />
                @break

            @case('formComment')
                <x-submission.form-comment :formComment="$item['formComment']" />
                @break
        @endswitch
    @endforeach
</div>