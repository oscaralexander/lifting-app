<?php

use App\Enums\CountryCode;
use App\Models\Client;
use App\Services\OutsmartService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    #[Computed]
    public function clients(): LengthAwarePaginator
    {
        return Client::withCount('inspections')
            ->orderBy('name')
            ->paginate(Client::PER_PAGE, pageName: 'p')
            ->setPath(route('clients'));
    }

    public function delete(int $id): void
    {
        // Client::findOrFail($id)->delete();
        $this->dispatch('toast', message: __('clients.toast.deleted'), type: 'success');
    }

    public function importFromOutsmart(): void
    {
        $relations = app(OutsmartService::class)->getRelations();

        foreach ($relations as $relation) {
            Client::updateOrCreate(
                ['outsmart_id' => $relation['id']],
                [
                    'outsmart_debtor_number' => $relation['debtor_number'],
                    'name' => $relation['name'],
                    'address' => trim(($relation['street'] ?? '') . ' ' . ($relation['house_number'] ?? '')),
                    'postal_code' => $relation['postal_code'] ?? '',
                    'city' => $relation['city'] ?? '',
                    'country' => (CountryCode::tryFrom(strtolower(trim($relation['country'] ?? ''))) ?? CountryCode::NL)->value,
                    'contact_name' => $relation['contact'] ?? null,
                    'contact_email' => $relation['email'] ?? null,
                    'contact_phone' => $relation['phone_number'] ?? null,
                ]
            );
        }

        unset($this->clients);

        $this->dispatch('toast', message: __('clients.toast.imported', ['count' => count($relations)]), type: 'success');
    }

    public function render()
    {
        return $this->view()
            ->title(__('clients.index.title'));
    }
}
?>

<div>
    <x-header :title="__('clients.index.title')">
        <x-slot:actions>
            <x-btn
                icon="refresh-cw"
                primary
                wire:click="importFromOutsmart"
                wire:loading.attr="disabled"
                wire:loading.class="is-spinning"
                wire:target="importFromOutsmart"
            >@lang('clients.index.btn_import_from_outsmart')</x-btn>
        </x-slot:actions>
    </x-header>
    <div class="u-stack u-stack-gap-xl">
        <div class="u-stack u-stack-gap-l">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">@lang('clients.index.col_name')</th>
                        <th scope="col">@lang('clients.index.col_debtor_number')</th>
                        <th scope="col">@lang('clients.index.col_city')</th>
                        <th scope="col">@lang('clients.index.col_contact')</th>
                        <th scope="col">@lang('clients.index.col_contact_phone')</th>
                        <th class="table__num" scope="col">@lang('clients.index.col_inspections')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->clients as $client)
                        <tr wire:key="client-{{ $client->id }}">
                            <td>
                                @if ($client->outsmart_debtor_number)
                                    <a
                                        href="#"
                                        wire:click.prevent="$dispatch('openModal', { component: 'clients.projects-modal', arguments: { clientId: {{ $client->id }} } })"
                                    >{{ $client->name }}</a>
                                @else
                                    {{ $client->name }}
                                @endif
                            </td>
                            <td>{{ $client->outsmart_debtor_number }}</td>
                            <td>{{ $client->city }}, {{ $client->country->labelShort() }}</td>
                            <td>
                                @if (!empty($client->contact_email))
                                    <a href="mailto:{{ $client->contact_email }}">{{ $client->contact_name }}</a>
                                @else
                                    {{ $client->contact_name }}
                                @endif
                            </td>
                            <td>
                                @if (!empty($client->contact_phone))
                                    <a href="tel:{{ str_replace(' ', '', $client->contact_phone) }}">{{ $client->contact_phone }}</a>
                                @endif
                            </td>
                            <td class="table__num">{{ $client->inspections_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $this->clients->links('livewire::custom') }}
        </div>
    </div>
</div>