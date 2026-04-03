<?php

namespace App\Livewire\Schemas;

use App\Constants\Event;
use App\Enums\InspectionObject\Type;
use App\Models\Form;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;

class SchemaModal extends ModalComponent
{
    public $description;

    #[Locked]
    public ?int $id = null;

    public $name;

    public ?string $type = null;

    public function mount(?int $id): void
    {
        $this->id = $id;

        if ($id) {
            $this->name = $this->schema->name;
            $this->description = $this->schema->description;
            $this->type = $this->schema->type?->value;
        }
    }

    #[Computed]
    public function schema(): Form
    {
        return Form::findOrNew($this->id);
    }

    public function render(): View
    {
        return view('livewire.schemas.schema-modal', [
            'typeOptions' => Type::options(),
        ]);
    }

    public function rules(): array
    {
        return [
            'description' => ['nullable', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'in:' . implode(',', array_column(Type::cases(), 'value'))],
        ];
    }

    public function submit(): void
    {
        $this->validate();

        $schema = $this->schema;
        $schema->name = $this->name;
        $schema->description = $this->description;
        $schema->type = $this->type ? Type::from($this->type) : null;
        $schema->save();

        $this->dispatch('toast', message: __('schemas.toast.' . ($schema->wasRecentlyCreated ? 'created' : 'updated')), type: 'success');

        $this->closeModalWithEvents([
            Event::REFRESH,
        ]);
    }
}
