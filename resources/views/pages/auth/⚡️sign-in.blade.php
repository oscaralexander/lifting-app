<?php

use App\Constants\CookieKey;
use App\Constants\SessionKey;
use App\Models\User;
use App\Traits\HasRatelimitedEndpoints;
use Livewire\Attributes\Layout;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

new class extends Component
{
    use HasRatelimitedEndpoints;

    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    private function authenticate(): void
    {
        $validated = $this->validate();

        // 10 attempts per 10 minutes per email and IP address
        $this->ensureIsNotRateLimited(
            errorKey: 'email',
            errorMessage: 'auth.sign_in.throttled',
            maxAttempts: 10,
        );

        if (!auth('web')->attempt(Arr::only($validated, ['email', 'password']), Arr::get($validated, 'remember', false))) {
            RateLimiter::hit($this->throttleKey(), 10 * 60);

            throw ValidationException::withMessages([
                'auth' => __('auth.sign_in.failed'),
            ]);
        }

        $user = User::where('email', $this->email)->first();

        if ($user->locale) {
            Cookie::queue(Cookie::make(
                name: CookieKey::LOCALE,
                value: $user->locale->value ?? config('app.locale'),
            ));
        }

        if (Arr::get($validated, 'remember', false)) {
            $emailCookie = Cookie::make(
                minutes: 400 * 24 * 60, // see https://httpwg.org/http-extensions/draft-ietf-httpbis-rfc6265bis.html#section-5.5
                name: CookieKey::EMAIL,
                sameSite: 'strict',
                value: $this->email,
            );
            Cookie::queue($emailCookie);
        } else {
            // Remove cookie when Remember flag is set to false
            Cookie::queue(Cookie::forget(CookieKey::EMAIL));
        }

        Cookie::queue(Cookie::make(
            name: CookieKey::LOCALE,
            value: 'nl',
        ));

        RateLimiter::clear($this->throttleKey());
    }

    public function mount(): void
    {
        $this->email = request()->input('email', request()->cookie(CookieKey::EMAIL, ''));
    }

    public function render()
    {
        return $this->view()
            ->title(__('auth.sign_in.title'));
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['boolean'],
        ];
    }

    public function submit(): void
    {
        $this->authenticate();
        session()->regenerate();

        if (!auth('web')->user()->hasVerifiedEmail()) {
            $this->redirectRoute(name: 'verification.notice', navigate: true);
            return;
        }

        $this->redirectIntended(route('admin', absolute: false));
    }

    private function throttleKey(): string
    {
        return 'signin|' . Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }
}
?>

<div>
    <div class="u-stack u-stack-gap-xxl">
        <header class="guest__header">
            <a href="{{ route('admin') }}" wire:navigate><img alt="Lifting Inspections" class="guest__logo" src="/assets/img/lifting-inspections-fc.svg"></a>
        </header>
        <p class="guest__intro u-text-center">
            {!! __('auth.sign_in.intro') !!}
        </p>
        @if (session()->has(SessionKey::STATUS))
            <div class="guest__status" x-transition:enter.opacity.duration.500ms>
                <x-icon icon="key-round" />
                {{ session()->get(SessionKey::STATUS) }}
            </div>
        @endif
        <x-form class="u-stack u-stack-gap-m">
            <fieldset class="guest__credentials">
                <x-form.input
                    large
                    model="email"
                    :placeholder="__('user.label_email')"
                    required
                />
                <div
                    class="input input--block input--large password"
                    x-data="{
                        isPasswordVisible: false,
                        togglePasswordVisibility() {
                            this.isPasswordVisible = !this.isPasswordVisible;
                        }
                    }"
                >
                    <input
                        @error('password')
                            aria-description="{{ $message }}"
                            aria-invalid="true"
                        @else
                            aria-description="{{ __('user.label_password') }}"
                        @enderror
                        aria-label="password"
                        name="password"
                        placeholder="{{ __('user.label_password') }}"
                        required
                        type="password"
                        wire:model="password"
                        x-bind:type="isPasswordVisible ? 'text' : 'password'"
                    >
                    <button
                        aria-label="{{ __('ui.toggle_password_visibility') }}"
                        class="password__toggle"
                        type="button"
                        x-on:click="togglePasswordVisibility"
                    >
                        <span class="password__icon" x-show="!isPasswordVisible"><x-icon icon="eye" /></span>
                        <span class="password__icon" x-show="isPasswordVisible"><x-icon icon="eye-off" /></span>
                    </button>
                </div>
            </fieldset>
            @error('auth')
                <div class="guest__authError">{{ $message }}</div>
            @enderror
            <div class="u-flex u-flex-align-center u-flex-justify-between">
                <x-form.lightswitch
                    model="remember"
                    :text="__('auth.sign_in.remember')"
                />
                <x-btn primary submit>@lang('auth.sign_in.btn')</x-btn>
            </div>
            <div class="guest__passwordReset">
                <x-btn :href="route('auth.forgot-password')" icon="key-round" text>@lang('auth.sign_in.forgot_password')</x-btn>
            </div>
        </x-form>
    </div>
</div>