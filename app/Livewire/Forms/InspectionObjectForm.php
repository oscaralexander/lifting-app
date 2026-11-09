<?php

namespace App\Livewire\Forms;

use App\Enums\InspectionObject\Attachment\Type as AttachmentType;
use App\Enums\InspectionObject\Type;
use App\Models\InspectionObject;
use Illuminate\Validation\Rule;
use Livewire\Form;

class InspectionObjectForm extends Form
{
    public $asset_number;

    public ?string $attachment_type = null;

    public ?string $attachment_type_other = null;

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
        $this->attachment_type = $inspectionObject->attachment_type?->value;
        $this->attachment_type_other = $inspectionObject->attachment_type_other;
    }

    /**
     * Validate the form, only keeping the attachment type (and its custom
     * description) when they apply.
     *
     * @return array<string, mixed>
     */
    public function validatedData(): array
    {
        $data = $this->validate();

        if ($this->type !== Type::ATTACHMENT) {
            $data['attachment_type'] = null;
        }

        if ($data['attachment_type'] !== AttachmentType::OTHER->value) {
            $data['attachment_type_other'] = null;
        }

        return $data;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::enum(Type::class)],
            'attachment_type' => [Rule::requiredIf($this->type === Type::ATTACHMENT), 'nullable', Rule::enum(AttachmentType::class)],
            'attachment_type_other' => [Rule::requiredIf($this->type === Type::ATTACHMENT && $this->attachment_type === AttachmentType::OTHER->value), 'nullable', 'string', 'max:255'],
            'manufacturer' => ['required', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'serial_number' => ['nullable', 'string', 'max:255'],
            'asset_number' => ['nullable', 'string', 'max:255'],
            'year_manufacture' => ['nullable', 'integer', 'min:1900', 'max:2099'],
        ];
    }
}
