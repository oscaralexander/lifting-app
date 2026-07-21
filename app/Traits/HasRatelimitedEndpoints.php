<?php

namespace App\Traits;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

trait HasRatelimitedEndpoints
{
    /**
     * Ensure the request to send another email is not rate limited.
     *
     * Allow 2 emails every "passwordless_link_expiration" seconds
     *
     * @throws ValidationException
     */
    private function ensureIsNotRateLimited(int $maxAttempts, string $errorKey, string $errorMessage): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), $maxAttempts)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());
        $minutes = ceil($seconds / 60);

        $time = $minutes > 0 ? $minutes.' minutes' : $seconds.'seconds';

        throw ValidationException::withMessages([
            $errorKey => __($errorMessage, [
                'time' => $time,
            ]),
        ]);
    }
}
