<?php

use App\Constants\Event;
use App\Models\InspectionObject;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    #[Computed]
    public function inspectionObjects(): LengthAwarePaginator
    {
        return InspectionObject::withCount('inspections')->paginate(10);
    }

    #[On(Event::REFRESH)]
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
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">@lang('inspection_objects.index.col_name')</th>
                    <th scope="col">@lang('inspection_objects.index.col_type')</th>
                    <th scope="col">@lang('inspection_objects.index.col_next_inspection_before')</th>
                    <th class="table__num" scope="col">@lang('inspection_objects.index.col_inspections')</th>
                    <th scope="col">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->inspectionObjects as $inspectionObject)
                    <tr>
                        <td>
                            <a href="{{ route('inspection-objects.show', $inspectionObject) }}" wire:navigate>{{ $inspectionObject->name ?: 'Geen naam' }}</a>
                        </td>
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
                @endforeach
            </tbody>
        </table>
    </div>
</div>
