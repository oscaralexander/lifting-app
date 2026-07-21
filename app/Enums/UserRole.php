<?php

namespace App\Enums;

use Illuminate\Support\Collection;

enum UserRole: string
{
    case ADMIN = 'admin';
    case USER = 'user';

    public static function options(): Collection
    {
        return collect(self::cases())->mapWithKeys(fn (self $userRole) => [$userRole->value => __('user_role.'.$userRole->value)]);
    }

    public static function values(): array
    {
        return collect(self::cases())->pluck('value')->toArray();
    }
}
