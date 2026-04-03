<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use App\Livewire\Forms\UserForm;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Edit extends Component
{
    public UserForm $form;

    public User $user;

    public function mount(User $user): void
    {
        Gate::authorize('update', $user);

        $this->user = $user;
        $this->form->setUser($user);
    }

    #[On('toast')]
    public function render()
    {
        return view('livewire.admin.users.edit');
    }

    public function submit(): void
    {
        $this->user = $this->form->save();
        $this->dispatch('toast', message: __('users.toast.updated'), type: 'success');
    }
}
