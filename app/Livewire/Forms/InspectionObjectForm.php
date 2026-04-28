<?php

namespace App\Livewire\Forms;

use App\Enums\InspectionObject\Type;
use App\Models\InspectionObject;
use Illuminate\Validation\Rule;
use Livewire\Form;

class InspectionObjectForm extends Form
{
    public $asset_number;

    public $manufacturer;

    public $model;

    public $serial_number;

    public ?Type $type = null;

    public $year_manufacture;

    public function init(InspectionObject $inspectionObject, Type $type): void
    {
        $this->type = $type;
        $this->manufacturer = $inspectionObject->manufacturer;
        $this->model = $inspectionObject->model;
        $this->serial_number = $inspectionObject->serial_number;
        $this->asset_number = $inspectionObject->asset_number;
        $this->year_manufacture = $inspectionObject->year_manufacture;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::enum(Type::class)],
            'manufacturer' => ['required', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'serial_number' => ['nullable', 'string', 'max:255'],
            'asset_number' => ['nullable', 'string', 'max:255'],
            'year_manufacture' => ['nullable', 'integer', 'min:1900', 'max:2099'],
        ];
    }
}
