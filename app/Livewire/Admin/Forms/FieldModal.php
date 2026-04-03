<?php

namespace App\Livewire\Admin\Forms;

use App\Constants\Event;
use App\Enums\FieldType;
use App\Models\Field;
use App\Models\Form;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;
use LivewireUI\Modal\ModalComponent;

class FieldModal extends ModalComponent
{
    public $attrs = [];

    #[Locked]
    public ?int $formId = null;

    #[Locked]
    public ?int $id = null;

    public $description_en;

    public $description_fr;

    public $description_nl;

    public $label_en;

    public $label_fr;

    public $label_nl;

    public FieldType $type;

    public $values_en;

    public $values_fr;

    public $values_nl;

    #[Computed]
    public function field(): Field
    {
        return $this->id
            ? Field::findOrFail($this->id)
            : new Field();
    }

    #[Computed]
    public function fields(): Collection
    {
        if (empty($this->search)) {
            return Field::orderBy('label_' . app()->getLocale())->get();
        }

        return Field::search($this->search)->get();
    }

    #[Computed]
    public function form(): Form
    {
        return Form::findOrFail($this->formId);
    }

    public function mount(?int $formId, ?int $id): void
    {
        $this->formId = $formId;
        $this->id = $id;

        if ($this->id) {
            $this->attrs = $this->field->attrs;
            $this->description_en = $this->field->description_en;
            $this->description_fr = $this->field->description_fr;
            $this->description_nl = $this->field->description_nl;
            $this->label_en = $this->field->label_en;
            $this->label_fr = $this->field->label_fr;
            $this->label_nl = $this->field->label_nl;
            $this->values_en = $this->field->values_en;
            $this->values_fr = $this->field->values_fr;
            $this->values_nl = $this->field->values_nl;
            $this->type = $this->field->type;
        }
    }

    public function render(): View
    {
        return view('livewire.admin.forms.field-modal');
    }

    public function rules(): array
    {
        $rules = [
            'label_en' => ['required', 'string'],
            'label_fr' => ['required', 'string'],
            'label_nl' => ['required', 'string'],
        ];

        if ($this->type === FieldType::SELECT || $this->type === FieldType::SELECT_MULTIPLE) {
            $rules['values_nl'] = ['required', 'string'];
            $rules['values_en'] = ['required', 'string'];
            $rules['values_fr'] = ['required', 'string'];
        }

        return $rules;
    }

    public function selectFieldType(FieldType $type): void
    {
        $this->type = $type;
    }

    public function submit(): void
    {
        $this->validate();
        $exists = $this->field->exists;

        $this->field->attrs = $this->attrs;
        $this->field->description_en = $this->description_en;
        $this->field->description_fr = $this->description_fr;
        $this->field->description_nl = $this->description_nl;
        $this->field->label_en = $this->label_en;
        $this->field->label_fr = $this->label_fr;
        $this->field->label_nl = $this->label_nl;
        $this->field->values_en = $this->values_en;
        $this->field->values_fr = $this->values_fr;
        $this->field->values_nl = $this->values_nl;
        $this->field->type = $this->type;
        $this->field->save();

        // Attach newly created field to the form
        if (!$exists && $this->form->id) {
            $this->form->fields()->attach($this->field->id, ['required' => false, 'public' => false]);
        }

        $this->dispatch('toast', message: __('fields.toast.' . ($exists ? 'updated' : 'created')), type: 'success');

        $this->closeModalWithEvents([
            Edit::class => Event::REFRESH,
        ]);
    }
}
