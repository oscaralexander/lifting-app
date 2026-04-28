<?php

namespace App\Livewire\Forms;

use App\Enums\InspectionObject\OperatorLift\BaseMount;
use App\Models\InspectionObjects\OperatorLift;
use Illuminate\Validation\Rule;
use Livewire\Form;

class OperatorLiftForm extends Form
{
    public ?BaseMount $base_mount = null;

    public function init(OperatorLift $operatorLift): void
    {
        $this->base_mount = $operatorLift->base_mount;
    }

    public function rules(): array
    {
        return [
            'base_mount' => ['nullable', Rule::enum(BaseMount::class)],
        ];
    }
}
