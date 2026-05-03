<?php

namespace App\Livewire\Schemas;

use App\Constants\Event;
use App\Livewire\Concerns\HandlesDecimalInput;
use App\Models\FieldGroup;
use App\Models\Form;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;

class FieldGroupModal extends ModalComponent
{
    use HandlesDecimalInput;

    protected array $decimalProperties = ['number'];

    #[Locked]
    public ?int $id = null;

    #[Locked]
    public int $formId;

    public string $name;

    public string $number;

    #[Computed]
    public function fieldGroup(): FieldGroup
    {
        $fieldGroup = FieldGroup::findOrNew($this->id);
        $fieldGroup->form()->associate($this->formId);

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
            $this->name = $this->fieldGroup->name;
            $this->number = $this->fieldGroup->number;
        }
    }

    public function render(): View
    {
        return view('livewire.schemas.field-group-modal');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'number' => ['required', 'numeric'],
        ];
    }

    public function submit(): void
    {
        $this->normalizeDecimalInputs();
        $this->validate();
        $exists = $this->fieldGroup->exists;

        if (! $exists) {
            $this->fieldGroup->position = $this->form->getNextPosition();
        }

        $this->fieldGroup->name = $this->name;
        $this->fieldGroup->number = $this->number;
        $this->fieldGroup->save();

        $this->dispatch('toast', message: __('form_builder.toast.field_group_saved'), type: 'success');
        $this->closeModalWithEvents([Event::REFRESH]);
    }
}
