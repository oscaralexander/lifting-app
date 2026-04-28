<?php

namespace App\Livewire\Forms;

use App\Enums\InspectionType;
use App\Models\Inspection;
use Illuminate\Validation\Rule;
use Livewire\Form;

class InspectionForm extends Form
{
    public ?string $type = null;

    public ?int $clientId = null;

    public int $form_id;

    public Inspection $inspection;

    public int $inspection_object_id;

    public $projectName;

    public $projectAddress;

    public $projectPostalCode;

    public $projectCity;

    public function init(Inspection $inspection, int $formId, int $inspectionObjectId): void
    {
        $this->inspection = $inspection;
        $this->form_id = $formId;
        $this->inspection_object_id = $inspectionObjectId;

        if ($inspection->exists) {
            $this->type = $inspection->type?->value;
            $this->clientId = $inspection->client_id;
            $this->projectName = $inspection->project_name;
            $this->projectAddress = $inspection->project_address;
            $this->projectPostalCode = $inspection->project_postal_code;
            $this->projectCity = $inspection->project_city;
        }
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::enum(InspectionType::class)],
            'clientId' => ['required', Rule::exists('clients', 'id')],
            'projectName' => ['required', 'string', 'max:255'],
            'projectAddress' => ['nullable', 'string', 'max:255'],
            'projectPostalCode' => ['nullable', 'string', 'max:20'],
            'projectCity' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function save(): string
    {
        $this->validate();

        $this->inspection->form_id = $this->form_id;
        $this->inspection->inspection_object_id = $this->inspection_object_id;

        $this->inspection->type = InspectionType::from($this->type);
        $this->inspection->client_id = $this->clientId;
        $this->inspection->project_name = $this->projectName;
        $this->inspection->project_address = $this->projectAddress;
        $this->inspection->project_postal_code = $this->projectPostalCode;
        $this->inspection->project_city = $this->projectCity;
        $this->inspection->save();

        return $this->inspection->hash;
    }
}
