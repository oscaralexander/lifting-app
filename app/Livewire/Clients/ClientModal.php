<?php

namespace App\Livewire\Clients;

use App\Constants\Event;
use App\Enums\CountryCode;
use App\Models\Client;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;

class ClientModal extends ModalComponent
{
    public string $address;

    public string $city;

    public string $contact_name;

    public string $contact_email;

    public string $contact_phone;

    public CountryCode $country = CountryCode::NL;

    #[Locked]
    public ?int $id = null;

    public string $name;

    public string $postal_code;

    #[Computed]
    public function client(): Client
    {
        return Client::findOrNew($this->id);
    }

    #[Computed]
    public function contactHasErrors(): bool
    {
        return $this->getErrorBag()->hasAny([
            'name',
            'email',
            'phone_no',
        ]);
    }

    public function mount(?int $id): void
    {
        $this->id = $id;

        if ($id) {
            $this->name = $this->client->name;
            $this->address = $this->client->address;
            $this->city = $this->client->city;
            $this->country = $this->client->country;
            $this->postal_code = $this->client->postal_code;
            $this->contact_name = $this->client->contact_name;
            $this->contact_email = $this->client->contact_email;
            $this->contact_phone = $this->client->contact_phone;
        }
    }

    public function render(): View
    {
        return view('livewire.clients.client-modal');
    }

    #[Computed]
    public function phonePlaceholder(): string
    {
        return match ($this->country) {
            CountryCode::BE => '+32 ...',
            CountryCode::NL => '+31 ...',
            CountryCode::FR => '+33 ...',
        };
    }

    public function postalCodePattern(): string
    {
        return match ($this->country) {
            CountryCode::NL => '/^\d{4}\s?[A-Za-z]{2}$/', // e.g. 1234 AB or 1234AB
            CountryCode::BE => '/^\d{4}$/', // e.g. 1000
            CountryCode::FR => '/^\d{5}$/', // e.g. 75001
        };
    }

    #[Computed]
    public function postalCodePlaceholder(): string
    {
        return match ($this->country) {
            CountryCode::NL => '1234 AB',
            CountryCode::BE => '1000',
            CountryCode::FR => '75001',
        };
    }

    public function rules(): array
    {
        return [
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:255', 'regex:/^\+\d{1,3}\s?.+$/'],
            'country' => ['required', Rule::enum(CountryCode::class)],
            'name' => ['required', 'string', Rule::unique('clients', 'name')->ignore($this->id)],
            'postal_code' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function submit(): void
    {
        $this->validate();

        $client = $this->client;
        $client->name = $this->name;
        $client->address = $this->address;
        $client->city = $this->city;
        $client->contact_name = $this->contact_name;
        $client->contact_email = $this->contact_email;
        $client->contact_phone = $this->contact_phone;
        $client->country = $this->country->value;
        $client->postal_code = preg_replace('/^(\d{4})\s?([A-Za-z]{2})$/', '$1 $2', $this->postal_code);
        $client->save();

        $this->dispatch('toast', message: __('clients.toast.saved'), type: 'success');

        $this->closeModalWithEvents([
            [Event::CLIENT_SAVED, ['id' => $client->id]],
        ]);
    }
}
