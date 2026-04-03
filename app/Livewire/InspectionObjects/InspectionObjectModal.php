<?php

namespace App\Livewire\InspectionObjects;

use App\Constants\Event;

use App\Enums\InspectionObject\Type;
use App\Enums\CountryCode;
use App\Livewire\Forms\CraneForm;
use App\Livewire\Forms\InspectionObjectForm;
use App\Livewire\Forms\OperatorLiftForm;
use App\Models\Client;
use App\Models\InspectionObject;
use App\Models\InspectionObjects\Crane;
use App\Models\InspectionObjects\OperatorLift;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use LivewireUI\Modal\ModalComponent;

class InspectionObjectModal extends ModalComponent
{
    #[Locked]
    public ?int $id = null;

    public InspectionObjectForm $form;

    public CraneForm|OperatorLiftForm $inspectableForm;

    public Type $type;

    #[Computed]
    public function clients(): array
    {
        return Client::orderBy('name')->get()->mapWithKeys(fn ($client) => [$client->id => $client->name])->toArray();
    }

    #[Computed]
    public function inspectable(): Crane|OperatorLift
    {
        if ($this->type === Type::CRANE) {
            return $this->inspectionObject->inspectable ?? new Crane();
        }

        if ($this->type === Type::OPERATOR_LIFT) {
            return $this->inspectionObject->inspectable ?? new OperatorLift();
        }

        throw new \Exception('Invalid type: ' . $this->type);
    }

    #[Computed]
    public function inspectionObject(): InspectionObject
    {
        return InspectionObject::with('inspectable')->findOrNew($this->id);
    }

    public function mount(Type $type, ?int $id = null): void
    {
        $this->id = $id;
        $this->type = $type;

        // Initialize Inspection Object form
        $inspectionObject = $this->inspectionObject;

        // Initialize Inspectable form)
        if ($type === Type::CRANE) {
            $this->inspectableForm = new CraneForm($this, 'inspectableForm');
        } else {
            $this->inspectableForm = new OperatorLiftForm($this, 'inspectableForm');
        }

        $this->form->init($this->inspectionObject, $this->type);
        $this->inspectableForm->init($this->inspectable);
    }

    #[On(Event::CLIENT_CREATED)]
    public function onClientCreated(int $id): void
    {
        $this->form->setClientId($id);
    }

    #[Computed]
    public function postalCodePlaceholder(): string
    {
        return match ($this->form->country) {
            CountryCode::BE => '1000',
            CountryCode::FR => '75001',
            default => '1234 AB',
        };
    }

    public function render(): View
    {
        return view('livewire.inspection-objects.inspection-object-modal');
    }

    public function submit(): void
    {
        $inspectionObjectData = $this->form->validate();
        $inspectableData = $this->inspectableForm->validate();

        DB::beginTransaction();

        // Save Inspection Object
        $inspectionObject = $this->inspectionObject;
        $inspectionObject->fill($inspectionObjectData);
        $inspectionObject->save();

        // Save Inspectable
        $inspectable = $this->inspectable;
        $inspectable->fill($inspectableData);
        $inspectable->save();

        // Attach Inspectable to Inspection Object
        $this->inspectionObject->inspectable()->associate($inspectable);
        $this->inspectionObject->save();

        DB::commit();

        $this->dispatch('toast', message: __('inspection_objects.toast.' . ($inspectionObject->wasRecentlyCreated ? 'created' : 'updated')), type: 'success');
        $this->closeModalWithEvents([Event::REFRESH]);
    }
}
