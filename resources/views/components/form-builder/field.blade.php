@props([
    'disabled' => false,
    'field' => null,
    'formId' => null,
])

<div
    @if ($disabled)
        aria-disabled="true"
    @endif
    @class([
        'formBuilder__field',
        'formBuilder__field--disabled' => $disabled,
        'js-formBuilderField',
    ])
    data-field-id="{{ $field->id }}"
    data-sortable-item
    @if ($field->pivot)
        data-pivot-id="{{ $field->pivot->id }}"
        wire:key="field-{{ $field->pivot->id }}"
    @else
        wire:key="field-{{ $field->id }}"
    @endif
>
    <x-icon class="formBuilder__icon" :icon="$field->type->icon()" />
    <div @class([
        'formBuilder__fieldFlex',
        'formBuilder__fieldFlex--required' => $field->pivot?->required,
    ])>
        <div class="formBuilder__fieldTruncate">
            @unless (empty($field->number))
                <b>{{ $field->number }}</b> &middot;
            @endunless
            {{ $field->label }}
        </div>
    </div>
    <div class="formBuilder__fieldActions">
        {{--
        @if ($field->pivot)
            <button
                @class([
                    'formBuilder__fieldAction',
                    'formBuilder__fieldRequired',
                    'formBuilder__fieldRequired--required' => $field->pivot->required,
                ])
                wire:click="toggleRequired({{ $field->pivot->id }})"
            >
                <x-icon icon="asterisk" />
            </button>
        @endif
        --}}
        <x-popout icon="ellipsis-vertical" position="tl" small transparent>
            @if (!$field->pivot)
                <x-popout.item :label="__('field.btn_add_to_form')" wire:click="addToForm({{ $field->id }})" />
            @endif
            <x-popout.item icon="pencil" :label="__('field.btn_edit')" wire:click="$dispatch('openModal', { arguments: { id: {{ $field->id }}, formId: {{ $formId ?? 'null' }} }, component: 'schemas.field-modal' });" />
            @if ($field->pivot)
                <x-popout.item danger icon="trash" :label="__('field.btn_delete')" wire:click="deleteField({{ $field->pivot->id }})" wire:confirm="{{ __('field.confirm_delete') }}" />
            @else
                <x-popout.item danger icon="trash" :label="__('field.btn_delete')" wire:click="$dispatch('openModal', { arguments: { id: {{ $field->id }} }, component: 'schemas.field-delete-modal' });" />
            @endif
        </x-popout>
    </div>
</div>