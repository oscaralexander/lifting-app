@props([
    'fieldGroup' => null,
    'formId' => null,
])

<div
    @class([
        'formBuilder__fieldGroup',
        'formBuilder__fieldGroup--empty' => $fieldGroup->fields->isEmpty(),
    ])
    data-field-group-id="{{ $fieldGroup->id }}"
    data-sortable-item
    wire:key="fieldGroup-{{ $fieldGroup->id }}"
    x-data="{
        init() {
            this.isExpanded = !(localStorage.getItem(this.key) === '0');
        },
        isExpanded: true,
        key: 'fieldGroup-{{ $fieldGroup->id }}',
        toggleExpanded() {
            this.isExpanded = !this.isExpanded;
            localStorage.setItem(this.key, this.isExpanded ? '1' : '0');
        },
    }"
>
    <header class="formBuilder__fieldGroupHeader">
        <x-icon class="formBuilder__icon" icon="group" />
        <div class="formBuilder__fieldGroupFlex">
            @unless (empty($fieldGroup->number))
                <b>{{ $fieldGroup->number }}</b> &middot;
            @endunless
            {{ $fieldGroup->name }}
        </div>
        <div class="formBuilder__fieldGroupActions">
            <button class="formBuilder__fieldAction" x-on:click="toggleExpanded()">
                <x-icon icon="plus" x-show="!isExpanded" />
                <x-icon icon="minus" x-show="isExpanded" />
            </button>
            <x-popout icon="ellipsis-vertical" position="tl" small transparent>
                <x-popout.item
                    icon="pencil"
                    :label="__('ui.edit')"
                    wire:click="$dispatch('openModal', { arguments: { id: {{ $fieldGroup->id }}, formId: {{ $fieldGroup->form_id ?? 'null' }} }, component: 'schemas.field-group-modal' });"
                />
                <x-popout.item icon="copy" :label="__('ui.duplicate')" wire:click="duplicateFieldGroup({{ $fieldGroup->id }})" />
                <x-popout.item danger icon="x" :label="__('ui.delete')" wire:click="deleteFieldGroup({{ $fieldGroup->id }})" wire:confirm="{{ __('forms.edit.confirm_delete_field_group') }}" />
            </x-popout>
        </div>
    </header>
    <div x-cloak x-show="isExpanded">
        <div class="formBuilder__groupedFields js-formBuilderSortable" data-empty-text="{{ __('forms.edit.group_empty') }}">
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
            @foreach ($items as $item)
                @if ($item['type'] === 'field')
                    <x-form-builder.field
                        :field="$item['field']"
                        :form-id="$formId"
                        wire:key="field-{{ $item['field']->pivot->id }}"
                        wire:sort:item="field-{{ $item['field']->pivot->id }}"
                    />
                @elseif ($item['type'] === 'formComment')
                    <x-form-builder.form-comment :form-comment="$item['formComment']" :form-id="$formId" />
                @endif
            @endforeach
        </div>
    </div>
</div>