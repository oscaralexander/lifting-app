@props([
    'formComment' => null,
    'formId' => null,
])

<div
    @class([
        'formBuilder__formComment',
        'js-formBuilderField',
    ])
    data-form-comment-id="{{ $formComment->id }}"
    data-sortable-item
    wire:key="formComment-{{ $formComment->id }}"
>
    <x-icon icon="message-circle" />
    <div class="formBuilder__fieldFlex">
        <div class="formBuilder__comment">{{ $formComment->comment }}</div>
    </div>
    <div class="formBuilder__fieldActions">
        <x-popout icon="ellipsis-vertical" position="tl" small transparent>
            <x-popout.item icon="pencil" :label="__('field.btn_edit')" wire:click="$dispatch('openModal', { arguments: { id: {{ $formComment->id }}, formId: {{ $formId ?? 'null' }} }, component: 'schemas.form-comment-modal' });" />
            <x-popout.item danger icon="x" :label="__('ui.delete')" wire:click="deleteFormComment({{ $formComment->id }})" wire:confirm="{{ __('forms.edit.confirm_delete_form_comment') }}" />
        </x-popout>
    </div>
</div>