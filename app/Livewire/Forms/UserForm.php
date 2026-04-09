<?php

namespace App\Livewire\Forms;

use App\Constants\CookieKey;
use App\Enums\Locale;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;
use Livewire\Form;

class UserForm extends Form
{
    public string $first_name;

    public string $email;

    public string $last_name;

    public string $password;

    public string $password_confirmation;

    public string $role = UserRole::USER->value;

    public array $inventories = [];

    public User $user;

    public function messages()
    {
        return [
            'role.enum' => __('users.create.errors.role_enum'),
            'email.unique' => __('users.create.errors.email_unique'),
        ];
    }

    protected function rules(): array
    {
        $rules = [
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user->id)],
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'password' => ['nullable', 'sometimes', 'confirmed', Password::defaults()],
            'role' => ['nullable', 'sometimes', new Enum(UserRole::class)],
        ];

        return $rules;
    }

    public function save(): User
    {
        $this->validate();

        // Check if user is a previously deleted user
        $deletedUser = User::onlyTrashed()->where('email', $this->email)->first();

        if ($deletedUser) {
            // Reset and restore deleted user
            $this->user = $deletedUser;
            $this->user->password = null;
            $this->user->token = Str::random(64);
            $this->user->restore();
        }

        $this->user->email = $this->email;
        $this->user->first_name = $this->first_name;
        $this->user->last_name = $this->last_name;
        $this->user->role = $this->role;

        if ($this->user->exists) {
            // Existing user
            if (! empty($this->password)) {
                $this->user->password = Hash::make($this->password);
            }
        } else {
            // New user
            $this->user->token = Str::random(64);
        }

        $this->user->save();
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
        $this->email = $user->email ?? '';
        $this->first_name = $user->first_name ?? '';
        $this->last_name = $user->last_name ?? '';
        $this->role = $user->role->value ?? UserRole::USER->value;
    }
}
