<?php

use Livewire\Component;

new class extends Component
{
    public function render()
    {
        return $this->view()
            ->title(__('dashboard.title'));
    }
}
?>

<div>
    <x-header :title="__('dashboard.title')" />
</div>