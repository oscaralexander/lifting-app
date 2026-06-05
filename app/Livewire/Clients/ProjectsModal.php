<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use App\Services\OutsmartService;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;

class ProjectsModal extends ModalComponent
{
    #[Locked]
    public int $clientId;

    #[Locked]
    public string $clientName = '';

    #[Locked]
    public ?string $debtorNumber = null;

    public bool $loaded = false;

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $workOrders = [];

    public function mount(int $clientId): void
    {
        $client = Client::findOrFail($clientId);

        $this->clientId = $client->id;
        $this->clientName = $client->name;
        $this->debtorNumber = $client->outsmart_debtor_number;
    }

    public function load(OutsmartService $outsmart): void
    {
        if ($this->debtorNumber) {
            $this->workOrders = $outsmart->getWorkOrders($this->debtorNumber);
        }

        $this->loaded = true;
    }

    public function render(): View
    {
        return view('livewire.clients.projects-modal');
    }

    public static function modalMaxWidth(): string
    {
        return '3xl';
    }
}
