<?php

namespace App\Livewire\Admin\Forms;

use App\Constants\Event;
use App\Models\Field;
use App\Models\FieldForm;
use App\Models\FieldGroup;
use App\Models\Form;
use App\Models\FormComment;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class Edit extends Component
{
    public int $formId;

    public string $search = '';

    public function addToForm(int $id)
    {
        $maxFieldGroupPosition = $this->form->fieldGroups()->max('position') ?? 0;
        $maxFieldPosition = $this->form->fields()->get()->max(fn ($field) => $field->pivot->position ?? 0) ?? 0;
        $maxFormCommentPosition = $this->form->formComments()->max('position') ?? 0;
        $position = max($maxFieldGroupPosition, $maxFieldPosition, $maxFormCommentPosition) + 1;

        // Use FieldForm model directly to allow duplicate fields on the same form
        FieldForm::create([
            'field_id' => $id,
            'form_id' => $this->formId,
            'position' => $position,
            'required' => false,
            'public' => false,
        ]);

        $this->dispatch(Event::TOAST, message: __('forms.toast.field_added'), type: 'success');
    }

    #[Computed]
    public function fields(): Collection
    {
        if (empty($this->search)) {
            // return Field::orderBy('label_' . app()->getLocale())->get();
            return collect();
        }

        return Field::search($this->search)
            ->orderBy('label_'.app()->getLocale())
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
            ->findOrFail($this->formId);
    }

    public function mount(int $formId)
    {
        $this->formId = $formId;
        // $this->form->load('fields', 'fieldGroups.fields', 'fieldGroups.formComments', 'formComments');
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
                'required' => false,
                'public' => false,
            ]);
        };

        foreach ($positions as $item) {
            if (is_array($item)) {
                if (! empty($item['fieldId'])) {
                    if (! empty($item['pivotId'])) {
                        // Update existing Field
                        FieldForm::where('id', $item['pivotId'])->update(['field_group_id' => null, 'position' => $position]);
                    } else {
                        // Create new Field
                        $createField($item['fieldId'], $position);
                    }

                    $position++;
                }

                if (! empty($item['formCommentId'])) {
                    // FormComment (ungrouped)
                    FormComment::where('id', $item['formCommentId'])->update(['field_group_id' => null, 'position' => $position]);
                    $position++;
                }

                if (! empty($item['fieldGroupId'])) {
                    // FieldGroup
                    $fieldGroupId = $item['fieldGroupId'];
                    $groupItems = $item['items'];

                    // Update FieldGroup position
                    FieldGroup::where('id', $fieldGroupId)->update(['position' => $position]);
                    $position++;

                    foreach ($groupItems as $groupItem) {
                        if (! empty($groupItem['fieldId'])) {
                            if (! empty($groupItem['pivotId'])) {
                                // Update existing Field
                                FieldForm::where('id', $groupItem['pivotId'])->update(['field_group_id' => $fieldGroupId, 'position' => $position]);
                            } else {
                                // Create new Field
                                $createField($groupItem['fieldId'], $position, $fieldGroupId);
                            }
                        }

                        if (! empty($groupItem['formCommentId'])) {
                            // FormComment (grouped)
                            FormComment::where('id', $groupItem['formCommentId'])->update([
                                'field_group_id' => $fieldGroupId,
                                'position' => $position,
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

    #[On(Event::REFRESH)]
    public function render()
    {
        $this->form->load([
            'fields' => function ($query) {
                $query->withPivot('id');
            },
            'formComments',
            'fieldGroups.fields' => function ($query) {
                $query->withPivot('id');
            },
            'fieldGroups.formComments',
        ]);

        return view('livewire.admin.forms.edit');
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

    public function deleteFormComment(int $id)
    {
        FormComment::where('id', $id)->delete();
    }

    public function toggleRequired(int $pivotId)
    {
        $required = $this->form->fields()->wherePivot('id', $pivotId)->first()->pivot->required ?? false;
        FieldForm::where('id', $pivotId)->update(['required' => ! $required]);
    }

    public function togglePublic(int $pivotId)
    {
        $public = $this->form->fields()->wherePivot('id', $pivotId)->first()->pivot->public ?? false;
        FieldForm::where('id', $pivotId)->update(['public' => ! $public]);
    }
}
