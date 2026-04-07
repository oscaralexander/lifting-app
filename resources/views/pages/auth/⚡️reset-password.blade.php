<?php

use App\Constants\SessionKey;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Livewire\Component;

new class extends Component
{
    public string $token = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(): void
    {
        $this->email = request()->input('email', '');
        $this->token = request()->input('token', '');
    }

    public function render()
    {
        return $this->view()
            ->title(__('auth.reset_password.title'));
    }

    public function submit(): void
    {
        $validated = $this->validate();

        $status = Password::reset(
            Arr::only($validated, ['email', 'password', 'password_confirmation', 'token']),
            function ($user) use ($validated) {
                $user->forceFill([
                    'password' => Hash::make($validated['password']),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            session()->flash(SessionKey::STATUS, __('guest.reset_password.flash_msg'));
            $this->redirectRoute('login');
            return;
        }

        $this->addError('email', __($status));
    }

    protected function rules(): array
    {
        return [
            'token' => ['required', 'string', 'size:64', 'ascii'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
            'password_confirmation' => ['required', 'same:password'],
        ];
    }
}
?>

<div>
    <div class="u-stack u-stack-gap-xxl">
        <header class="guest__header">
            <a href="{{ route('admin') }}" wire:navigate><img alt="Van der Spek" class="guest__logo" src="/assets/img/van-der-spek.svg"></a>
        </header>
        <p class="guest__intro u-text-center">
            {!! __('auth.forgot_password.intro') !!}
        </p>
        <x-form class="u-stack u-stack-gap-m">
            <input model="token" type="hidden">
            <fieldset class="fields">
                <x-form.input :label="__('user.label_email')" model="email" name="email" type="email" readonly required />
                <x-form.new-password :label="__('user.label_password_new')" model="password" name="password" required with-rules />
                <x-form.new-password :label="__('user.label_password_new_confirmation')" model="password_confirmation" name="password_confirmation" required />
                <div>
                    <button class="btn btn--primary" type="submit">{{ __('auth.reset_password.btn') }}</button>
                </div>
            </fieldset>
        </x-form>
    </div>
</div>
