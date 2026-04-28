<?php

use App\Models\Inspection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    #[Computed]
    public function inspections(): LengthAwarePaginator
    {
        return Inspection::query()
            ->with('inspectionObject', 'form', 'client')
            ->paginate(10);
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
                    <th scope="col">@lang('inspections.index.col_type')</th>
                    <th scope="col">@lang('inspections.index.col_client')</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->inspections as $inspection)
                    <tr>
                        <td>
                            <a
                                class="table__link"
                                href="{{ route('inspections.form', [
                                    'inspectionObjectId' => $inspection->inspection_object_id,
                                    'formSlug' => $inspection->form->slug,
                                    'inspectionHash' => $inspection->hash,
                                ]) }}"
                                wire:navigate
                            >{{ $inspection->project_name ?: 'Geen naam' }}</a>
                        </td>
                        <td>{{ $inspection->inspectionObject->type->label() }}</td>
                        <td>{{ $inspection->client?->name ?: 'Geen klant' }}</td>
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
    </div>
</div>