<?php

namespace App\Livewire\Forms;

use App\Models\InspectionObjects\OperatorLift;
use App\Enums\InspectionObject\OperatorLift\BaseMount;
use Illuminate\Validation\Rule;
use Livewire\Form;

class OperatorLiftForm extends Form
{
    public $asset_number;
    
    public ?BaseMount $base_mount = null;

    public $manufacturer;

    public $model;

    public $serial_number;

    public function init(OperatorLift $operatorLift): void
    {
        $this->asset_number = $operatorLift->asset_number;
        $this->base_mount = $operatorLift->base_mount;
        $this->manufacturer = $operatorLift->manufacturer;
        $this->model = $operatorLift->model;
        $this->serial_number = $operatorLift->serial_number;
    }

    public function rules(): array
    {
        return [
            'asset_number' => ['nullable', 'max:255'],
            'base_mount' => ['nullable', Rule::enum(BaseMount::class)],
            'manufacturer' => ['nullable', 'max:255'],
            'model' => ['nullable', 'max:255'],
            'serial_number' => ['nullable', 'max:255'],
        ];
    }
}
