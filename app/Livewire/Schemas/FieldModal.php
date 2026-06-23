<?php

namespace App\Livewire\Schemas;

use App\Constants\Event;
use App\Enums\FieldType;
use App\Livewire\Concerns\HandlesDecimalInput;
use App\Models\Field;
use App\Models\Form;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;

class FieldModal extends ModalComponent
{
    use HandlesDecimalInput;

    protected array $decimalProperties = ['number'];

    public $attrs = [];

    #[Locked]
    public ?int $formId = null;

    #[Locked]
    public ?int $id = null;

    public $description;

    public $label;

    public $number;

    public bool $required = true;

    public ?FieldType $type = null;

    public $values;

    #[Computed]
    public function field(): Field
    {
        return Field::findOrNew($this->id);
    }

    #[Computed]
    public function fields(): Collection
    {
        if (empty($this->search)) {
            return Field::orderBy('label')->get();
        }

        return Field::search($this->search)->get();
    }

    #[Computed]
    public function form(): Form
    {
        return Form::findOrFail($this->formId);
    }

    #[Computed]
    public function isAddedToForm(): bool
    {
        return $this->id !== null
            && $this->formId !== null
            && $this->form->fields()->whereKey($this->id)->exists();
    }

    public function mount(?int $formId, ?int $id): void
    {
        $this->formId = $formId;
        $this->id = $id;

        if ($this->id) {
            $this->attrs = $this->field->attrs;
            $this->description = $this->field->description;
            $this->label = $this->field->label;
            $this->number = $this->field->number;
            $this->type = $this->field->type;
            $this->values = $this->field->values;

            if ($this->isAddedToForm) {
                $this->required = (bool) $this->form->fields()->find($this->id)->pivot->required;
            }
        }
    }

    public function render(): View
    {
        return view('livewire.schemas.field-modal');
    }

    public function rules(): array
    {
        $rules = [
            'label' => ['required', 'string'],
            'number' => ['nullable', 'numeric'],
        ];

        if ($this->type === FieldType::SELECT || $this->type === FieldType::SELECT_MULTIPLE) {
            $rules['values'] = ['required', 'string'];
        }

        return $rules;
    }

    public function selectFieldType(FieldType $type): void
    {
        $this->type = $type;
    }

    public function submit(): void
    {
        $this->normalizeDecimalInputs();
        $this->validate();
        $exists = $this->field->exists;

        $this->field->attrs = $this->attrs;
        $this->field->description = $this->description;
        $this->field->label = $this->label;
        $this->field->number = $this->number;
        $this->field->type = $this->type;
        $this->field->values = $this->values;
        $this->field->save();

        // Attach newly created field to the form
        if (! $exists && $this->form->id) {
            $position = $this->form->getNextPosition();

            $this->form->fields()->attach($this->field->id, [
                'position' => $position,
                'required' => $this->required,
            ]);
        } elseif ($this->isAddedToForm) {
            $this->form->fields()->updateExistingPivot($this->field->id, [
                'required' => $this->required,
            ]);
        }

        $this->dispatch('toast', message: __('form_builder.toast.field_saved'), type: 'success');

        $this->closeModalWithEvents([
            Event::REFRESH,
        ]);
    }

    public function unsetFieldType(): void
    {
        $this->type = null;
    }
}
