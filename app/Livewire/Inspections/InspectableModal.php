<?php

namespace App\Livewire\Inspections;

use App\Constants\Event;
use App\Enums\InspectionObject\Type;
use App\Livewire\Concerns\HandlesDecimalInput;
use App\Livewire\Forms\CraneForm;
use App\Livewire\Forms\OperatorLiftForm;
use App\Models\InspectionObjects\Crane;
use App\Models\InspectionObjects\OperatorLift;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;

class InspectableModal extends ModalComponent
{
    use HandlesDecimalInput;

    protected array $decimalProperties = [
        'inspectableForm.hook_height',
        'inspectableForm.central_ballast',
        'inspectableForm.counter_ballast',
        'inspectableForm.base_length',
        'inspectableForm.base_width',
        'inspectableForm.base_rail_track_gauge',
        'inspectableForm.base_rail_wheelbase',
        'inspectableForm.base_crane_track_length',
        'inspectableForm.boom_length',
    ];

    #[Locked]
    public ?int $inspectableId = null;

    public CraneForm|OperatorLiftForm $inspectableForm;

    #[Locked]
    public Type $type;

    #[Computed]
    public function inspectable(): Crane|OperatorLift|null
    {
        switch ($this->type) {
            case Type::CRANE:
                return Crane::findOrNew($this->inspectableId);

            case Type::OPERATOR_LIFT:
                return OperatorLift::findOrNew($this->inspectableId);

            default:
                return null;
        }
    }

    public function mount(Type $type, ?int $inspectableId = null): void
    {
        $this->type = $type;
        $this->inspectableId = $inspectableId;

        switch ($type) {
            case Type::CRANE:
                $this->inspectableForm = new CraneForm($this, 'inspectableForm');
                break;

            case Type::OPERATOR_LIFT:
                $this->inspectableForm = new OperatorLiftForm($this, 'inspectableForm');
                break;
        }

        if ($this->inspectableForm) {
            $this->inspectableForm->init($this->inspectable);
        }
    }

    public function render(): View
    {
        return view('livewire.inspections.inspectable-modal');
    }

    public function submit(): void
    {
        $this->normalizeDecimalInputs();

        $data = $this->inspectableForm->validate();

        // Convert empty strings to null
        $data = array_map(fn ($value) => $value === '' ? null : $value, $data);

        $inspectable = $this->inspectable;
        $inspectable->fill($data);
        $inspectable->save();

        $this->dispatch(Event::INSPECTABLE_SAVED,
            inspectableId: $inspectable->id,
            inspectableType: get_class($inspectable),
        );
        $this->dispatch(Event::TOAST, message: __('inspections.inspectable.toast.saved'), type: 'success');

        $this->closeModal();
    }
}
