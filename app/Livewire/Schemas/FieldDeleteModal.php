<?php

namespace App\Livewire\Schemas;

use App\Constants\Event;
use App\Models\Field;
use App\Models\FieldForm;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;

class FieldDeleteModal extends ModalComponent
{
    #[Locked]
    public ?int $id = null;

    #[Computed]
    public function field(): ?Field
    {
        return Field::withCount('forms')->find($this->id);
    }

    #[Computed]
    public function formCount(): int
    {
        return $this->field?->forms_count ?? 0;
    }

    public function mount(int $id): void
    {
        $this->id = $id;
    }

    public function render(): View
    {
        return view('livewire.schemas.field-delete-modal');
    }

    public function submit(): void
    {
        FieldForm::where('field_id', $this->id)->delete();
        Field::where('id', $this->id)->delete();

        $this->dispatch('toast', message: __('fields.toast.deleted'), type: 'success');

        $this->closeModalWithEvents([
            Event::REFRESH,
        ]);
    }
}
