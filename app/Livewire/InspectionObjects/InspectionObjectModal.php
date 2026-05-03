<?php

namespace App\Livewire\InspectionObjects;

use App\Constants\Event;
use App\Enums\InspectionObject\Type;
use App\Livewire\Forms\InspectionObjectForm;
use App\Models\InspectionObject;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;

class InspectionObjectModal extends ModalComponent
{
    #[Locked]
    public ?int $id = null;

    public InspectionObjectForm $form;

    public Type $type;

    #[Computed]
    public function inspectionObject(): InspectionObject
    {
        return InspectionObject::findOrNew($this->id);
    }

    public function mount(Type $type, ?int $id = null): void
    {
        $this->id = $id;
        $this->type = $type;

        $this->form->init($this->inspectionObject, $this->type);
    }

    public function render(): View
    {
        return view('livewire.inspection-objects.inspection-object-modal');
    }

    public function submit(): void
    {
        $inspectionObjectData = $this->form->validate();

        $inspectionObject = $this->inspectionObject;
        $inspectionObject->fill($inspectionObjectData);
        $inspectionObject->save();

        $this->dispatch(Event::TOAST, message: __('inspection_objects.toast.saved'), type: 'success');
        $this->closeModalWithEvents([Event::INSPECTION_OBJECT_SAVED]);
    }
}
