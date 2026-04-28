<?php

namespace App\Livewire\Inspections;

use App\Constants\Event;
use App\Enums\InspectionType;
use App\Livewire\Forms\InspectionForm;
use App\Models\Client;
use App\Models\Inspection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;

class InspectionModal extends ModalComponent
{
    #[Locked]
    public int $inspectionObjectId;

    #[Locked]
    public int $formId;

    #[Locked]
    public ?string $inspectionHash = null;

    public InspectionForm $form;

    #[Computed]
    public function types(): array
    {
        return collect(InspectionType::cases())
            ->mapWithKeys(fn (InspectionType $type) => [$type->value => $type->label()])
            ->toArray();
    }

    #[Computed]
    public function clients(): array
    {
        return Client::orderBy('name')->get()->mapWithKeys(fn ($client) => [$client->id => $client->name])->toArray();
    }

    #[Computed]
    public function inspection(): Inspection
    {
        return Inspection::firstOrNew([
            'hash' => $this->inspectionHash,
        ], [
            'form_id' => $this->formId,
            'inspection_object_id' => $this->inspectionObjectId,
        ]);
    }

    public function mount(int $formId, int $inspectionObjectId, ?string $inspectionHash = null): void
    {
        $this->formId = $formId;
        $this->inspectionHash = $inspectionHash;
        $this->inspectionObjectId = $inspectionObjectId;

        $this->form->init($this->inspection, $formId, $inspectionObjectId);
    }

    public function render(): View
    {
        return view('livewire.inspections.inspection-modal');
    }

    public function submit(): void
    {

        $hash = $this->form->save();

        $this->dispatch(Event::INSPECTION_SAVED, inspectionHash: $hash);
        $this->dispatch(Event::TOAST, message: __('inspections.project.toast.saved'), type: 'success');

        $this->closeModal();
    }
}
