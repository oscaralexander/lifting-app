<?php

namespace App\Policies;

use App\Models\Settings;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SettingsPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin;
    }

    public function view(User $user, Settings $settings): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Settings $settings): bool
    {
        return false;
    }

    public function delete(User $user, Settings $settings): bool
    {
        return false;
    }

    public function restore(User $user, Settings $settings): bool
    {
        return false;
    }

    public function forceDelete(User $user, Settings $settings): bool
    {
        return false;
    }
}
