<?php

namespace App\Livewire\Schemas;

use App\Constants\Event;
use App\Models\Field;
use App\Models\FieldForm;
use App\Models\FieldGroup;
use App\Models\Form;
use App\Models\FormComment;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;

new class extends Component
{
    #[Locked]
    public int $formId;

    #[Locked]
    public string $formSlug;

    public string $search = '';

    public function addToForm(int $id)
    {
        $maxFieldGroupPosition = $this->form->fieldGroups()->max('position') ?? 0;
        $maxFieldPosition = $this->form->fields()->get()->max(fn($field) => $field->pivot->position ?? 0) ?? 0;
        $maxFormCommentPosition = $this->form->formComments()->max('position') ?? 0;
        $position = max($maxFieldGroupPosition, $maxFieldPosition, $maxFormCommentPosition) + 1;

        // Use FieldForm model directly to allow duplicate fields on the same form
        FieldForm::create([
            'field_id' => $id,
            'form_id' => $this->formId,
            'position' => $position,
            'required' => false,
        ]);

        $this->dispatch(Event::TOAST, message: __('forms.toast.field_added'), type: 'success');
    }

    #[Computed]
    public function fields(): Collection
    {
        if (empty($this->search)) {
            return collect();
        }

        return Field::search($this->search)
            ->orderBy('label')
            ->get();
    }

    #[Computed]
    public function fieldsCount(): int
    {
        return Field::count();
    }

    #[Computed]
    public function form(): Form
    {
        return Form::with('fields', 'fieldGroups.fields', 'fieldGroups.formComments', 'formComments')
            ->where('slug', $this->formSlug)
            ->firstOrFail();
    }

    public function mount(string $formSlug)
    {
        $this->formSlug = $formSlug;
        $this->formId = $this->form->id;
    }

    #[On(Event::FORM_UPDATED)]
    public function onSorted(array $positions): void
    {
        $formId = $this->formId;
        $position = 1;

        $createField = function (int $fieldId, int $position, ?int $fieldGroupId = null) use ($formId) {
            FieldForm::create([
                'field_id' => $fieldId,
                'field_group_id' => $fieldGroupId,
                'form_id' => $formId,
                'position' => $position,
                'required' => true,
            ]);
        };

        foreach ($positions as $item) {
            if (is_array($item)) {
                if (!empty($item['fieldId'])) {
                    if (!empty($item['pivotId'])) {
                        // Update existing Field
                        FieldForm::where('id', $item['pivotId'])->update(['field_group_id' => null, 'position' => $position]);
                    } else {
                        // Create new Field
                        $createField($item['fieldId'], $position);
                    }

                    $position++;
                }

                if (!empty($item['formCommentId'])) {
                    // FormComment (ungrouped)
                    FormComment::where('id', $item['formCommentId'])->update(['field_group_id' => null, 'position' => $position]);
                    $position++;
                }

                if (!empty($item['fieldGroupId'])) {
                    // FieldGroup
                    $fieldGroupId = $item['fieldGroupId'];
                    $groupItems = $item['items'];

                    // Update FieldGroup position
                    FieldGroup::where('id', $fieldGroupId)->update(['position' => $position]);
                    $position++;

                    foreach ($groupItems as $groupItem) {
                        if (!empty($groupItem['fieldId'])) {
                            if (!empty($groupItem['pivotId'])) {
                                // Update existing Field
                                FieldForm::where('id', $groupItem['pivotId'])->update(['field_group_id' => $fieldGroupId, 'position' => $position]);
                            } else {
                                // Create new Field
                                $createField($groupItem['fieldId'], $position, $fieldGroupId);
                            }
                        }

                        if (!empty($groupItem['formCommentId'])) {
                            // FormComment (grouped)
                            FormComment::where('id', $groupItem['formCommentId'])->update([
                                'field_group_id' => $fieldGroupId,
                                'position' => $position
                            ]);
                        }

                        $position++;
                    }
                }
            }
        }

        $this->dispatch(Event::FORM_SORTED);
        $this->dispatch(Event::TOAST, message: __('forms.toast.sorted'), type: 'success');
    }

    #[On(Event::FORM_SORTED)]
    #[On(Event::REFRESH)]
    public function refresh()
    {
        unset($this->form);
    }

    public function render()
    {
        return $this->view()
            ->title(__('schemas.index.title'));
    }

    public function deleteField(int $pivotId)
    {
        FieldForm::where('id', $pivotId)->delete();
        $this->dispatch(Event::TOAST, message: __('forms.toast.field_deleted'), type: 'success');
    }

    public function deleteFieldGroup(int $id)
    {
        // Delete all field_form records that belong to this field group
        FieldForm::where('field_group_id', $id)->delete();

        // Delete the field group itself
        FieldGroup::where('id', $id)->delete();
    }

    public function duplicateFieldGroup(int $id)
    {
        $source = FieldGroup::with('fields', 'formComments')->findOrFail($id);
        $form = $this->form;

        $duplicate = FieldGroup::create([
            'form_id' => $source->form_id,
            'name' => $source->name,
            'number' => $source->number,
            'position' => $form->getNextPosition(),
        ]);

        foreach ($source->fields as $field) {
            FieldForm::create([
                'field_id' => $field->id,
                'field_group_id' => $duplicate->id,
                'form_id' => $source->form_id,
                'position' => $form->getNextPosition(),
                'required' => $field->pivot->required,
            ]);
        }

        foreach ($source->formComments as $formComment) {
            FormComment::create([
                'field_group_id' => $duplicate->id,
                'form_id' => $source->form_id,
                'comment' => $formComment->comment,
                'position' => $form->getNextPosition(),
            ]);
        }

        $this->dispatch(Event::TOAST, message: __('form_builder.toast.field_group_duplicated'), type: 'success');
        $this->dispatch(Event::REFRESH);
    }

    public function deleteFormComment(int $id)
    {
        FormComment::where('id', $id)->delete();
    }

    public function toggleRequired(int $pivotId)
    {
        $required = $this->form->fields()->wherePivot('id', $pivotId)->first()->pivot->required ?? false;
        FieldForm::where('id', $pivotId)->update(['required' => !$required]);
        $this->dispatch(Event::REFRESH);
    }
}
?>

@php
    $items = collect();

    // Add field groups
    foreach ($this->form->fieldGroups->sortBy('position') as $fieldGroup) {
        $items->push([
            'fieldGroup' => $fieldGroup,
            'position' => $fieldGroup->position ?? 0,
            'type' => 'fieldGroup',
        ]);
    }

    // Add comments not in any group
    $formComments = $this->form->formComments->filter(function($formComment) {
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
    $groupedFieldPivotIds = $this->form->fieldGroups->flatMap(function($fieldGroup) {
        return $fieldGroup->fields->pluck('pivot.id');
    })->unique();

    foreach ($this->form->fields->sortBy('pivot.position') as $field) {
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

<div>
    <x-header
        :intro="$this->form->description"
        :title="$this->form->name"
        :path="[
            __('schemas.index.title') => route('schemas'),
        ]"
    />
    <div class="u-stack u-stack-gap-l">
        <div class="formBuilder js-formBuilder">
            <div class="formBuilder__col">
                <div class="formBuilder__sticky u-stack u-stack-gap-m">
                    <h3>@lang('schemas.edit.heading_form')</h3>
                    <div
                        class="formBuilder__form js-formBuilderForm js-formBuilderSortable"
                        data-empty-text="{{ __('schemas.edit.form_empty') }}"
                    >
                        @foreach ($items as $item)
                            @switch($item['type'])
                                @case('fieldGroup')
                                    <x-form-builder.field-group
                                        :form-id="$this->form->id"
                                        :field-group="$item['fieldGroup']"
                                    />
                                    @break
                                @case('field')
                                    <x-form-builder.field
                                        :field="$item['field']"
                                        :form-id="$this->form->id"
                                    />
                                    @break
                                @case('formComment')
                                    <x-form-builder.form-comment :form-comment="$item['formComment']" :form-id="$this->form->id" />
                                    @break
                            @endswitch
                        @endforeach
                    </div>
                    <div class="actions actions--flex">
                        <x-btn
                            icon="group"
                            primary
                            wire:click="$dispatch('openModal', { arguments: { id: null, formId: {{ $this->form->id }} }, component: 'schemas.field-group-modal' });"
                        >@lang('schemas.edit.btn_add_group')</x-btn>
                        <x-btn
                            icon="message-circle"
                            primary
                            wire:click="$dispatch('openModal', { arguments: { id: null, formId: {{ $this->form->id }} }, component: 'schemas.form-comment-modal' });"
                        >@lang('schemas.edit.btn_add_comment')</x-btn>
                    </div>
                    {{--
                    <div class="formBuilder__legend">
                        <div class="formBuilder__legendItem">
                            <div class="formBuilder__fieldAction formBuilder__fieldRequired formBuilder__fieldRequired--required">
                                <x-icon icon="asterisk" />
                            </div>
                            @lang('schemas.edit.legend_required')
                        </div>
                    </div>
                    --}}
                </div>
            </div>
            <div class="formBuilder__col">
                <div class="formBuilder__sticky u-stack u-stack-gap-m">
                    <h3>@lang('schemas.edit.heading_available_fields')</h3>
                    <div class="actions">
                        <x-form.input-search
                            autofocus
                            :placeholder="__('fields.modal.search_placeholder')"
                            wire:model.live.debounce.100ms="search"
                        />
                        <button class="btn btn--primary" type="button" wire:click="$dispatch('openModal', { component: 'schemas.field-modal', arguments: { id: null, formId: {{ $this->form->id }} }});">
                            <x-icon icon="plus" />
                            @lang('schemas.edit.btn_add_field')
                        </button>
                    </div>
                    <div class="formBuilder__fields js-formBuilderFields">
                        @if ($this->fields->isNotEmpty())
                            @foreach ($this->fields as $field)
                                <x-form-builder.field
                                    :field="$field"
                                    :form-id="$this->form->id"
                                />
                            @endforeach
                        @else
                            <div class="formBuilder__fieldsEmpty">
                                @if (!empty($this->search))
                                    <x-icon icon="search" />
                                    <p>@lang('schemas.edit.no_fields_found', ['query' => $search])</p>
                                @else
                                    <x-icon icon="search" />
                                    <p>@lang('schemas.edit.search', ['count' => $this->fieldsCount])</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
