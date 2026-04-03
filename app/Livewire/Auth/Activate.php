<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Activate extends Component
{
    #[Locked]
    public string $email;

    public string $password;

    public string $password_confirmation;

    #[Locked]
    public string $token;

    public User $user;

    public function mount(string $token): void
    {
        $this->user = User::where('token', $token)->firstOrFail();

        if (empty($token) || !is_null($this->user->password)) {
            // User already activated
            $this->redirectRoute('admin');
        }

        $this->email = $this->user->email;
        app()->setLocale($this->user->locale->value);
    }

    public function render(): View
    {
        return view('livewire.auth.activate');
    }

    public function submit(): void
    {
        $this->validate();

        $this->user->update([
            'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'password' => Hash::make($this->password),
            'token' => null,
        ]);

        auth('web')->login($this->user);
        $this->redirectRoute('admin');
    }

    protected function rules(): array
    {
        return [
            'token' => ['required', 'string', 'size:64', 'ascii', 'exists:users,token'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }
}
