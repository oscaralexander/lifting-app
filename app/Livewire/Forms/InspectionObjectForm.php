<?php

namespace App\Livewire\Forms;

use App\Enums\CountryCode;
use App\Enums\InspectionObject\Type;
use App\Models\InspectionObject;
use Illuminate\Validation\Rule;
use Livewire\Form;

class InspectionObjectForm extends Form
{
    public $address;

    public $city;

    public $client_id;

    public $country;

    public $description;

    public $postal_code;

    public ?Type $type = null;

    public function init(InspectionObject $inspectionObject, Type $type): void
    {
        $this->type = $type;
        $this->client_id = $inspectionObject->client_id;
        $this->address = $inspectionObject->address;
        $this->postal_code = $inspectionObject->postal_code;
        $this->city = $inspectionObject->city;
        $this->country = $inspectionObject->country ?? CountryCode::NL;
        $this->description = $inspectionObject->description;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::enum(Type::class)],
            'client_id' => ['required', 'exists:clients,id'],
            'address' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', Rule::enum(CountryCode::class)],
            'description' => ['nullable', 'string'],
        ];
    }

    public function setClientId(int $clientId): void
    {
        $this->client_id = $clientId;
    }
}
