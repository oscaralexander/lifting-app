<?php

use App\Models\Inspection;
use App\Enums\InspectionStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    #[Computed]
    public function inspections(): LengthAwarePaginator
    {
        return Inspection::query()
            ->with('inspectionObject', 'form', 'client')
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
        <x-slot:actions>
            <x-btn
                icon="plus"
                primary
                x-on:click="$dispatch('openModal', { component: 'cranes.crane-modal', arguments: { id: null } })"
            >@lang('inspections.index.btn_create')</x-btn>
        </x-slot:actions>
    </x-header>
    <div class="u-stack u-stack-gap-l">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">@lang('inspections.index.col_project_name')</th>
                    <th scope="col">@lang('inspections.index.col_status')</th>
                    <th scope="col">@lang('inspections.index.col_report')</th>
                    <th scope="col">@lang('inspections.index.col_type')</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->inspections as $inspection)
                    <tr>
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
                            <span
                                @class([
                                    'label' ,
                                    'label--danger' => in_array($inspection->status, [InspectionStatus::REJECTED, InspectionStatus::CAT_A_DEFICIENCIES, InspectionStatus::CAT_B_DEFICIENCIES]),
                                    'label--success' => $inspection->status === InspectionStatus::APPROVED,
                                ])
                            >{{ $inspection->status->label() }}</span>
                        </td>
                        <td><span class="u-text-nowrap">{{ $inspection->outsmart_order_number ?: '—' }}</span></td>
                        <td>{{ $inspection->inspectionObject->type->label() }}</td>
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
                @endforeach
            </tbody>
        </table>
        {{ $this->inspections->links('livewire::custom') }}
    </div>
</div>