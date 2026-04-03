<?php

namespace App\Livewire\Admin\Forms;

use App\Constants\Event;
use App\Models\FieldGroup;
use App\Models\Form;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;

class FieldGroupModal extends ModalComponent
{
    #[Locked]
    public ?int $id = null;

    #[Locked]
    public int $formId;

    public $name_en;

    public $name_fr;

    public $name_nl;

    #[Computed]
    public function fieldGroup(): FieldGroup
    {
        $fieldGroup = $this->id
            ? FieldGroup::findOrFail($this->id)
            : new FieldGroup();
        $fieldGroup->form_id = $this->formId;

        return $fieldGroup;
    }

    #[Computed]
    public function form(): Form
    {
        return Form::findOrFail($this->formId);
    }

    public function mount(int $formId, ?int $id): void
    {
        $this->formId = $formId;
        $this->id = $id;

        if ($this->id) {
            $this->name_nl = $this->fieldGroup->name_nl;
            $this->name_en = $this->fieldGroup->name_en;
            $this->name_fr = $this->fieldGroup->name_fr;
        }
    }

    public function render(): View
    {
        return view('livewire.admin.forms.field-group-modal');
    }

    public function rules(): array
    {
        return [
            'name_nl' => ['required', 'string'],
        ];
    }

    public function submit(): void
    {
        $this->validate();
        $exists = $this->fieldGroup->exists;

        $this->fieldGroup->name_en = $this->name_en ?? '';
        $this->fieldGroup->name_fr = $this->name_fr ?? '';
        $this->fieldGroup->name_nl = $this->name_nl;
        $this->fieldGroup->save();

        $this->dispatch('toast', message: __('field_groups.toast.' . ($exists ? 'updated' : 'created')), type: 'success');

        $this->closeModalWithEvents([
            Edit::class => Event::REFRESH,
        ]);
    }
}
