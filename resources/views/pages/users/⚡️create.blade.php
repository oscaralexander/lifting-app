<?php

use App\Livewire\Forms\UserForm;
use App\Mail\UserCreated;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

new class extends Component
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
        return $this->view()
            ->title(__('users.create.title'));
    }

    public function submit(): void
    {
        $this->user = $this->form->save();
        Mail::to($this->user->email)->send(new UserCreated($this->user, auth('web')->user()));
        session()->flash('toast', __('users.toast.created'));
        $this->redirect(route('users'), true);
    }
}
?>

<div>
    <x-header
        :title="__('users.create.title')"
        :path="[
            __('users.index.title') => route('users'),
        ]"
    />
    <x-form class="u-stack u-stack-gap-xl">
        @include('pages.users._form', ['user' => $this->user])
        <div class="actions">
            <button
                class="btn btn--primary"
                type="submit"
            >@lang('ui.save')</button>
            @can('viewAny', App\Models\User::class)
                <span>
                    @lang('ui.or')
                    <a href="{{ route('users') }}" wire:navigate>@lang('ui.cancel')</a>
                </span>
            @endcan
        </div>
    </x-form>
</div>
