<?php

namespace App\Models;

use App\Enums\Locale;
use App\Enums\UserRole;
use App\Mail\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_seen_at' => 'datetime',
            'locale' => Locale::class,
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    public function initials(): Attribute
    {
        return Attribute::get(
            fn (): string => Str::of(mb_substr($this->first_name, 0, 1).mb_substr($this->last_name, 0, 1))
                ->trim()
                ->upper()
                ->toString()
        );
    }

    public function isActive(): Attribute
    {
        return Attribute::get(
            fn (): bool => ! is_null($this->password)
        );
    }

    public function isAdmin(): Attribute
    {
        return Attribute::get(
            fn (): bool => $this->role === UserRole::ADMIN
        );
    }

    public function name(): Attribute
    {
        return Attribute::get(
            fn (): string => Str::of($this->first_name)
                ->append(' ')
                ->append($this->last_name)
                ->trim()
                ->toString()
        );
    }

    public function signature(): Attribute
    {
        return Attribute::get(
            fn (): string => asset('assets/img/signatures/'.$this->id.'.svg')
        );
    }

    public function inventories(): BelongsToMany
    {
        return $this->belongsToMany(Inventory::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $resetUrl = route('auth.reset-password', [
            'token' => $token,
            'email' => $this->email,
        ]);

        Mail::to($this->email)->send(new ResetPassword($this, $resetUrl));
    }
}
