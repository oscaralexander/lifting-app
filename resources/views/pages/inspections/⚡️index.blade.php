<?php

use Livewire\Component;

new class extends Component
{
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
</div>