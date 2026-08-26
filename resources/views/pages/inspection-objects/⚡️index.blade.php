<?php

use App\Constants\Event;
use App\Enums\InspectionObject\Type as InspectionObjectType;
use App\Models\InspectionObject;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public string $search = '';

    /** @var array<int, string> */
    public array $types = [];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedTypes(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function inspectionObjects(): LengthAwarePaginator
    {
        return InspectionObject::withCount('inspections')
            ->when($this->search, fn ($query) => $query->where(function ($q) {
                $q->where('manufacturer', 'like', '%' . $this->search . '%')
                    ->orWhere('model', 'like', '%' . $this->search . '%')
                    ->orWhere('serial_number', 'like', '%' . $this->search . '%');
            }))
            ->when($this->types, fn ($query) => $query->whereIn('type', $this->types))
            ->paginate(InspectionObject::PER_PAGE, pageName: 'p')
            ->setPath(route('inspection-objects'));
    }

    #[On(Event::INSPECTION_OBJECT_SAVED)]
    public function render()
    {
        return $this->view()
            ->title(__('inspection_objects.index.title'));
    }
}
?>

<div>
    <x-header :title="__('inspection_objects.index.title')">
        <x-slot:actions>
            <x-popout icon="plus" :label="__('inspection_objects.index.btn_create')" primary>
                <x-popout.item :label="__('inspection_objects.index.btn_create_crane')" wire:click="$dispatch('openModal', { component: 'inspection-objects.inspection-object-modal', arguments: { id: null, type: 'crane' } })" />
                <x-popout.item :label="__('inspection_objects.index.btn_create_operator_lift')" wire:click="$dispatch('openModal', { component: 'inspection-objects.inspection-object-modal', arguments: { id: null, type: 'operator_lift' } })" />
            </x-popout>
        </x-slot:actions>
    </x-header>
    <div class="u-stack u-stack-gap-l">
        <div class="u-flex u-flex-gap-m">
            <x-form.input-search wire:model.live.debounce.200ms="search" placeholder="Zoek op naam of serienummer" />
            <x-form.multi-select
                :options="InspectionObjectType::options()"
                :placeholder="__('inspection_objects.index.filter_type_placeholder')"
                wire:model.live="types"
            />
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">@lang('inspection_objects.index.col_name')</th>
                    <th scope="col">@lang('inspection_objects.index.col_serial_no')</th>
                    <th scope="col">@lang('inspection_objects.index.col_type')</th>
                    <th scope="col">@lang('inspection_objects.index.col_next_inspection_before')</th>
                    <th class="table__num" scope="col">@lang('inspection_objects.index.col_inspections')</th>
                    <th scope="col">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($this->inspectionObjects as $inspectionObject)
                    <tr>
                        <td>
                            <a href="{{ route('inspection-objects.show', $inspectionObject) }}" wire:navigate>{{ $inspectionObject->name ?: 'Geen naam' }}</a>
                        </td>
                        <td>{{ $inspectionObject->serial_number }}</td>
                        <td>{{ $inspectionObject->type->label() }}</td>
                        <td>{{ $inspectionObject->next_inspection_before }}</td>
                        <td class="table__num">{{ $inspectionObject->inspections_count }}</td>
                        <td>
                            <div class="table__actions">
                                <x-popout
                                    id="popout-inspection-object-{{ $inspectionObject->id }}"
                                    position="tl"
                                    small
                                    transparent
                                >
                                    <x-popout.item
                                        icon="pencil"
                                        :label="__('ui.edit')"
                                        :navigate="false"
                                        wire:click.prevent="$dispatch('openModal', { component: 'inspection-objects.inspection-object-modal', arguments: { id: {{ $inspectionObject->id }}, type: '{{ $inspectionObject->type->value }}' } })"
                                    />
                                    <x-popout.item
                                        danger
                                        icon="trash"
                                        :label="__('ui.delete')"
                                        wire:click.prevent="delete({{ $inspectionObject->id }})"
                                        wire:confirm="{{ __('inspection_objects.index.delete_confirm') }}"
                                    />
                                </x-popout>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="table__empty">@lang('inspection_objects.index.no_results', ['query' => $this->search])</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $this->inspectionObjects->links('livewire::custom') }}
    </div>
</div>
