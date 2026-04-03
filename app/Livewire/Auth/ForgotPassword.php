<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ForgotPassword extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    public bool $isEmailSent = false;

    public function render(): View
    {
        return view('livewire.auth.forgot-password');
    }

    public function submit(): void
    {
        // Send email
        $this->validate();

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_THROTTLED) {
            $this->addError('email', __('auth.forgot_password.throttled'));
            return;
        }

        if ($status !== Password::RESET_LINK_SENT) {
            $this->addError('email', $status);
            return;
        }

        $this->isEmailSent = Password::RESET_LINK_SENT;
    }
}
