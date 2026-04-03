<?php

namespace App\Livewire\Admin\Users;

use App\Mail\UserCreated;
use App\Models\User;
use App\Livewire\Forms\UserForm;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Create extends Component
{
    public UserForm $form;

    public User $user;

    public function mount(): void
    {
        Gate::authorize('create', User::class);

        $this->user = new User();
        $this->form->setUser($this->user);
    }

    public function render()
    {
        return view('livewire.admin.users.create');
    }

    public function submit(): void
    {
        $this->user = $this->form->save();
        Mail::to($this->user->email)->send(new UserCreated($this->user, auth('web')->user()));
        session()->flash('toast', __('users.toast.created'));
        $this->redirect(route('admin.users'), true);
    }
}
