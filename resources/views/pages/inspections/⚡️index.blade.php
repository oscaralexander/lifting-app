<?php

use App\Models\Inspection;
use App\Enums\InspectionStatus;
use App\Enums\InspectionType;
use App\Enums\InspectionObject\Type as InspectionObjectType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public string $search = '';

    /** @var array<int, string> */
    public array $types = [];

    /** @var array<int, string> */
    public array $statuses = [];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedTypes(): void
    {
        $this->resetPage();
    }

    public function updatedStatuses(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function inspections(): LengthAwarePaginator
    {
        return Inspection::query()
            ->with('inspectionObject', 'form', 'client')
            ->when($this->search, fn ($query) => $query->where(function ($q) {
                $q->where('project_name', 'like', '%' . $this->search . '%')
                    ->orWhere('outsmart_order_number', 'like', '%' . $this->search . '%')
                    ->orWhereHas('client', fn ($client) => $client->where('name', 'like', '%' . $this->search . '%'));
            }))
            ->when($this->types, fn ($query) => $query->whereHas(
                'inspectionObject',
                fn ($inspectionObject) => $inspectionObject->whereIn('type', $this->types)
            ))
            ->when($this->statuses, fn ($query) => $query->whereStatusIn($this->statuses))
            ->paginate(Inspection::PER_PAGE, pageName: 'p')
            ->setPath(route('inspections'));
    }

    public function render()
    {
        return $this->view()
            ->title(__('inspections.index.title'));
    }
}
?>

<div>
    <x-header :title="__('inspections.index.title')">
        {{--
        <x-slot:actions>
            <x-btn
                icon="plus"
                primary
                x-on:click="$dispatch('openModal', { component: 'cranes.crane-modal', arguments: { id: null } })"
            >@lang('inspections.index.btn_create')</x-btn>
        </x-slot:actions>
        --}}
    </x-header>
    <div class="u-stack u-stack-gap-l">
        <div class="u-flex u-flex-gap-m">
            <x-form.input-search wire:model.live.debounce.200ms="search" :placeholder="__('inspections.index.search_placeholder')" />
            <x-form.multi-select
                :options="InspectionObjectType::options()"
                :placeholder="__('inspections.index.filter_type_placeholder')"
                wire:model.live="types"
            />
            <x-form.multi-select
                :options="InspectionStatus::options()"
                :placeholder="__('inspections.index.filter_status_placeholder')"
                wire:model.live="statuses"
            />
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">@lang('inspections.index.col_project_name')</th>
                    <th scope="col"></th>
                    <th scope="col">@lang('inspections.index.col_report')</th>
                    <th scope="col">@lang('inspections.index.col_type')</th>
                    <th class="table__num" scope="col">@lang('inspections.index.col_status')</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($this->inspections as $inspection)
                    <tr wire:key="inspection-{{ $inspection->id }}">
                        <td>
                            <a
                                class="table__main"
                                href="{{ route('inspections.form', [
                                    'inspectionObjectId' => $inspection->inspection_object_id,
                                    'formSlug' => $inspection->form->slug,
                                    'inspectionHash' => $inspection->hash,
                                ]) }}"
                                wire:navigate
                            >{{ $inspection->project_name ?: 'Geen naam' }}</a>
                            @if ($inspection->client)
                                <div class="u-text-lc u-text-s">{{ $inspection->client->name }}</div>
                            @endif
                        </td>
                        <td>
                            @if ($inspection->type === InspectionType::TCVT)
                                <img alt="TCVT" class="table__logo" height="24" width="56" src="/assets/img/tcvt.svg" />
                            @endif
                        </td>
                        <td><span class="u-text-nowrap">{{ $inspection->outsmart_order_number ?: '—' }}</span></td>
                        <td>{{ $inspection->inspectionObject->type->label() }}</td>
                        <td class="table__num">
                            <span
                                @class([
                                    'label' ,
                                    'label--danger' => in_array($inspection->status, [InspectionStatus::REJECTED, InspectionStatus::CAT_A_DEFICIENCIES, InspectionStatus::CAT_B_DEFICIENCIES]),
                                    'label--success' => $inspection->status === InspectionStatus::APPROVED,
                                ])
                            >{{ $inspection->status->label() }}</span>
                        </td>
                        <td>
                            <div class="table__actions">
                                <x-popout
                                    id="popout-inspection-object-{{ $inspection->id }}"
                                    position="tl"
                                    small
                                    transparent
                                >
                                    <x-popout.item
                                        href="{{ route('inspections.form', [
                                            'inspectionObjectId' => $inspection->inspection_object_id,
                                            'formSlug' => $inspection->form->slug,
                                            'inspectionHash' => $inspection->hash,
                                        ]) }}"
                                        icon="pencil"
                                        :label="__('ui.edit')"
                                    />
                                    @if ($inspection->outsmart_work_order_id)
                                        <x-popout.item
                                            href="{{ $inspection->outsmart_url }}"
                                            icon="outsmart"
                                            :label="__('inspections.index.outsmart_link')"
                                            target="_blank"
                                        />
                                    @endif
                                    <x-popout.item
                                        danger
                                        icon="trash"
                                        :label="__('ui.delete')"
                                        wire:click.prevent="delete({{ $inspection->hash }})"
                                        wire:confirm="{{ __('inspection_objects.index.delete_confirm') }}"
                                    />
                                </x-popout>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="table__empty">@lang('inspections.index.no_results', ['query' => $this->search])</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $this->inspections->links('livewire::custom') }}
    </div>
</div>