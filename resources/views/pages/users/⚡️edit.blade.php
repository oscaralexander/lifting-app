<?php

use App\Models\User;
use Livewire\Component;

new class extends Component
{
    public User $user;

    public function mount(User $user): void
    {
    }

    public function render()
    {
        return $this->view()
            ->title(__('users.edit.title'));
    }
}
?>

<div>
    <h1>@lang('users.edit.title')</h1>
</div>