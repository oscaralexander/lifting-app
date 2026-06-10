<?php

namespace App\Livewire\Inspections;

use App\Constants\Event;
use App\Models\Client;
use App\Models\Form;
use App\Models\Inspection;
use App\Services\OutsmartService;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;

class StartInspectionModal extends ModalComponent
{
    #[Locked]
    public int $formId;

    #[Locked]
    public int $inspectionObjectId;

    #[Locked]
    public ?string $inspectionHash = null;

    public ?int $clientId = null;

    public ?string $workOrderId = null;

    public function mount(int $formId, int $inspectionObjectId, ?string $inspectionHash = null): void
    {
        $this->formId = $formId;
        $this->inspectionObjectId = $inspectionObjectId;
        $this->inspectionHash = $inspectionHash;

        if ($inspectionHash) {
            $inspection = Inspection::where('hash', $inspectionHash)->firstOrFail();

            $this->clientId = $inspection->client_id;
            $this->workOrderId = $inspection->outsmart_work_order_id;
        }
    }

    /**
     * The selected client's work orders, in a lightweight, payload-safe shape.
     *
     * The heavy `Photos` data is intentionally excluded so it never gets serialized into
     * the Livewire request payload; it is re-fetched server-side on submit instead.
     *
     * @return array<int, array<string, mixed>>
     */
    #[Computed]
    public function workOrders(): array
    {
        return collect($this->fetchWorkOrders())
            ->map(fn (array $order) => [
                'id' => $order['id'] ?? null,
                'OrderNr' => $order['OrderNr'] ?? null,
                'Reference' => $order['Reference'] ?? null,
                'status' => $order['status'] ?? null,
                'CreationDate' => $order['CreationDate'] ?? null,
                'CustomerStreet' => $order['CustomerStreet'] ?? null,
                'CustomerStreetNo' => $order['CustomerStreetNo'] ?? null,
                'CustomerZIP' => $order['CustomerZIP'] ?? null,
                'CustomerCity' => $order['CustomerCity'] ?? null,
            ])
            ->all();
    }

    /**
     * @return array<int, string>
     */
    #[Computed]
    public function clients(): array
    {
        return Client::orderBy('name')->get()
            ->mapWithKeys(fn (Client $client) => [$client->id => $client->name])
            ->toArray();
    }

    /**
     * @return array<string, string>
     */
    #[Computed]
    public function workOrderOptions(): array
    {
        return collect($this->workOrders)
            ->mapWithKeys(fn (array $order) => [
                (string) $order['id'] => trim($order['OrderNr'] ?? ''),
            ])
            ->toArray();
    }

    /**
     * @return array<string, mixed>|null
     */
    #[Computed]
    public function selectedWorkOrder(): ?array
    {
        if (! $this->workOrderId) {
            return null;
        }

        return collect($this->workOrders)
            ->first(fn (array $order) => (string) $order['id'] === (string) $this->workOrderId);
    }

    public function updatedClientId(): void
    {
        $this->workOrderId = null;
    }

    /**
     * Fetch the full, untrimmed work orders for the selected client from Outsmart.
     *
     * Cached briefly per debtor so the lazy island and subsequent re-renders (e.g. selecting
     * a work order) don't repeatedly hit the Outsmart API within a single modal session.
     *
     * @return array<int, array<string, mixed>>
     */
    private function fetchWorkOrders(): array
    {
        $client = $this->clientId ? Client::find($this->clientId) : null;

        if (! $client?->outsmart_debtor_number) {
            return [];
        }

        $debtorNumber = $client->outsmart_debtor_number;

        return cache()->remember(
            "outsmart.work_orders.{$debtorNumber}",
            now()->addMinutes(5),
            fn () => app(OutsmartService::class)->getWorkOrders($debtorNumber),
        );
    }

    /**
     * Resolve the inspector's full name from the work order's assigned employee.
     *
     * @param  array<string, mixed>  $workOrder
     */
    private function resolveInspectorName(array $workOrder): ?string
    {
        $employeeNr = $workOrder['EmployeeNr'] ?? null;

        if (! $employeeNr) {
            return null;
        }

        $employee = app(OutsmartService::class)->getEmployee((string) $employeeNr);

        if ($employee === null) {
            return null;
        }

        return trim(($employee['firstname'] ?? '').' '.($employee['lastname'] ?? '')) ?: null;
    }

    public function submit(): void
    {
        $this->validate([
            'clientId' => ['required', 'exists:clients,id'],
            'workOrderId' => ['required', 'string'],
        ]);

        $form = Form::findOrFail($this->formId);

        $workOrder = collect($this->fetchWorkOrders())
            ->first(fn (array $order) => (string) ($order['id'] ?? '') === (string) $this->workOrderId);

        abort_if($workOrder === null, 404);

        $attributes = [
            'client_id' => $this->clientId,
            'project_name' => ($workOrder['Reference'] ?? null) ?: ($workOrder['OrderNr'] ?? null),
            'project_address' => trim(($workOrder['CustomerStreet'] ?? '').' '.($workOrder['CustomerStreetNo'] ?? '')),
            'project_postal_code' => $workOrder['CustomerZIP'] ?? null,
            'project_city' => $workOrder['CustomerCity'] ?? null,
            'inspector_name' => $this->resolveInspectorName($workOrder),
            'outsmart_order_number' => $workOrder['OrderNr'] ?? null,
            'outsmart_photos' => $workOrder['Photos'] ?? null,
            'outsmart_work_order_id' => $workOrder['id'],
        ];

        if ($this->inspectionHash) {
            $inspection = Inspection::where('hash', $this->inspectionHash)->firstOrFail();
            $inspection->update($attributes);

            $this->dispatch(Event::INSPECTION_SAVED, inspectionHash: $inspection->hash);
            $this->dispatch(Event::TOAST, message: __('inspections.start.toast.relinked'), type: 'success');

            $this->closeModal();

            return;
        }

        $inspection = Inspection::create([
            ...$attributes,
            'form_id' => $form->id,
            'inspection_object_id' => $this->inspectionObjectId,
        ]);

        $this->redirectRoute('inspections.form', [
            'formSlug' => $form->slug,
            'inspectionObjectId' => $this->inspectionObjectId,
            'inspectionHash' => $inspection->hash,
        ], navigate: true);
    }

    public function render(): View
    {
        return view('livewire.inspections.start-inspection-modal');
    }

    public static function modalMaxWidth(): string
    {
        return '2xl';
    }
}
