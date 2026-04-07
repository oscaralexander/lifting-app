<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    public bool $isEmailSent = false;

    public function render()
    {
        return $this->view()
            ->title(__('auth.forgot_password.title'));
    }

    public function submit(): void
    {
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
?>

<div>
    <div class="u-stack u-stack-gap-xxl">
        <header class="guest__header">
            <a href="{{ route('admin') }}" wire:navigate><img alt="Van der Spek" class="guest__logo" src="/assets/img/van-der-spek.svg"></a>
        </header>
        <p class="guest__intro u-text-center">
            {!! __('auth.forgot_password.intro') !!}
        </p>
        <div
            class="u-stack u-stack-gap-xl guest__passwordResetSubmitted"
            x-show="$wire.isEmailSent"
            x-transition:enter.opacity.duration.500ms
        >
            <div class="u-stack u-stack-gap-m">
                <div class="guest__passwordResetSuccess">
                    <x-icon icon="mail-check" />
                    {{ __('auth.forgot_password.success') }}
                </div>
                <div class="guest__passwordResetSupport">
                    <p>{!! __('auth.forgot_password.support') !!}</p>
                </div>
            </div>
        </div>
        <x-form class="u-stack u-stack-gap-m">
            <x-form.input
                autofocus
                large
                model="email"
                :placeholder="__('user.label_email')"
                required
            />
            <div class="u-flex u-flex-justify-between">
                <a class="btn btn--text" href="{{ route('login') }}" wire:navigate>
                    <x-icon icon="arrow-left" />
                    {{ __('ui.back') }}
                </a>
                <button class="btn btn--primary" type="submit">{{ __('auth.forgot_password.send') }}</button>
            </div>
        </x-form>
    </div>
</div>
