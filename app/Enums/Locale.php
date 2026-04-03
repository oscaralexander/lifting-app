<?php

namespace App\Enums;

use Illuminate\Support\Collection;

enum Locale: string
{
    case EN = 'en';
    case NL = 'nl';
    case FR = 'fr';

    public function flag(): string
    {
        return match($this) {
            self::EN => '/assets/img/flags/gb.svg',
            self::NL => '/assets/img/flags/nl.svg',
            self::FR => '/assets/img/flags/fr.svg',
        };
    }

    public static function options(): Collection
    {
        return collect(self::cases())->mapWithKeys(fn (self $locale) => [$locale->value => __('locale.' . $locale->value)]);
    }

    public static function values(): array
    {
        return collect(self::cases())->pluck('value')->toArray();
    }
}
