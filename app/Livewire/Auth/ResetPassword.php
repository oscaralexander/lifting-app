<?php

namespace App\Livewire\Auth;

use App\Constants\SessionKey;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password as FacadesPassword;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class ResetPassword extends Component
{
    public string $token;

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function render(): View
    {
        $this->email = request()->input('email', '');
        $this->token = request()->input('token', '');
        return view('livewire.auth.reset-password');
    }

    public function submit(): void
    {
        $validated = $this->validate();

        $status = FacadesPassword::reset(
            Arr::only($validated, ['email', 'password', 'password_confirmation', 'token']),
            function ($user) use ($validated) {
                $user->forceFill([
                    'password' => Hash::make($validated['password']),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status == FacadesPassword::PASSWORD_RESET) {
            session()->flash(SessionKey::STATUS, __('guest.reset_password.flash_msg'));
            $this->redirectRoute('auth.sign-in');
            return;
        }

        $this->addError('email', __($status));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    protected function rules(): array
    {
        return [
            'token' => ['required', 'string', 'size:64', 'ascii'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'password_confirmation' => ['required', 'same:password'],
        ];
    }

}
