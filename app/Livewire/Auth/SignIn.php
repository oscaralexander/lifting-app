<?php

namespace App\Livewire\Auth;

use App\Constants\CookieKey;
use App\Constants\SessionKey;
use App\Models\User;
use App\Traits\HasRatelimitedEndpoints;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class SignIn extends Component
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
        return view('livewire.auth.sign-in', [
            'pageTitle' => __('auth.sign_in.title')
        ]);
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
