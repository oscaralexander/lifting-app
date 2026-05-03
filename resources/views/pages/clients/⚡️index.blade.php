<?php

use App\Constants\Event;
use App\Models\Client;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    #[Computed]
    public function clients(): LengthAwarePaginator
    {
        return Client::withCount('inspections')->paginate(10);
    }

    public function delete(int $id): void
    {
        // Client::findOrFail($id)->delete();
        $this->dispatch('toast', message: __('clients.toast.deleted'), type: 'success');
    }

    #[On(Event::CLIENT_SAVED)]
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
                icon="plus"
                primary
                x-on:click="$dispatch('openModal', { component: 'clients.client-modal', arguments: { id: null } })"
            >@lang('clients.index.btn_create')</x-btn>
        </x-slot:actions>
    </x-header>
    <div class="u-stack u-stack-gap-l">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">@lang('clients.index.col_name')</th>
                    <th scope="col">@lang('clients.index.col_city')</th>
                    <th scope="col">@lang('clients.index.col_contact')</th>
                    <th scope="col">@lang('clients.index.col_contact_phone')</th>
                    <th class="table__num" scope="col">@lang('clients.index.col_inspections')</th>
                    <th scope="col">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->clients as $client)
                    <tr wire:key="client-{{ $client->id }}">
                        <td>
                            <a
                                href="#"
                                x-on:click.prevent="$dispatch('openModal', { component: 'clients.client-modal', arguments: { id: {{ $client->id }} } })"
                            >{{ $client->name }}</a>
                        </td>
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
                        <td>
                            <div class="table__actions">
                                <x-popout
                                    id="popout-client-{{ $client->id }}"
                                    position="tl"
                                    small
                                    transparent
                                >
                                    <x-popout.item
                                        icon="pencil"
                                        :label="__('ui.edit')"
                                        x-on:click.prevent="$dispatch('openModal', { component: 'clients.client-modal', arguments: { id: {{ $client->id }} } })"
                                    />
                                    <x-popout.item
                                        danger
                                        icon="trash"
                                        :label="__('ui.delete')"
                                        wire:click.prevent="delete({{ $client->id }})"
                                        wire:confirm="{{ __('clients.index.delete_confirm') }}"
                                    />
                                </x-popout>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>