<?php

namespace App\Livewire\Admin\Users;

use App\Mail\UserCreated;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function delete(User $user): void
    {
        $user->delete();
        $this->dispatch('toast', message: __('users.toast.deleted'), type: 'success');
    }

    public function mount(): void
    {
        Gate::authorize('viewAny', User::class);

        if (session('toast')) {
            $this->dispatch('toast', message: session('toast'), type: 'success');
        }
    }

    #[Computed]
    public function users(): Collection
    {
        return User::all();
    }

    #[On('toast')]
    public function render()
    {
        return view('livewire.admin.users.index');
    }

    public function resendInvite(User $user): void
    {
        Mail::to($user->email)->send(new UserCreated($user, auth('web')->user()));
        $this->dispatch('toast', message: __('users.toast.invite_sent'), type: 'success');
    }
}
