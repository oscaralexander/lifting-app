<?php

namespace App\Livewire\Admin\Forms;

use App\Constants\Event;
use App\Enums\FormType;
use App\Models\Form;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;

class FormModal extends ModalComponent
{
    #[Locked]
    public ?int $id = null;

    public $name_en;

    public $name_fr;

    public $name_nl;

    public FormType $type;

    #[Computed]
    public function form(): Form
    {
        return $this->id
            ? Form::findOrFail($this->id)
            : new Form;
    }

    public function mount(?int $id): void
    {
        $this->id = $id;

        if ($this->id) {
            $this->name_nl = $this->form->name_nl;
            $this->name_en = $this->form->name_en;
            $this->name_fr = $this->form->name_fr;
            $this->type = $this->form->type ?? FormType::BASELINE_INSPECTION;
        }
    }

    public function render(): View
    {
        return view('livewire.admin.forms.form-modal');
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
        $exists = $this->form->exists;

        $this->form->name_en = $this->name_en ?? '';
        $this->form->name_fr = $this->name_fr ?? '';
        $this->form->name_nl = $this->name_nl;
        $this->form->type = $this->type ?? FormType::BASELINE_INSPECTION;
        $this->form->save();

        $this->dispatch('toast', message: __('forms.toast.saved'), type: 'success');

        $this->closeModalWithEvents([
            Index::class => Event::REFRESH,
        ]);
    }
}
