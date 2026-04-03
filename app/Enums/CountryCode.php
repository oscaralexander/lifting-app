<?php

namespace App\Enums;

use Illuminate\Support\Collection;

enum CountryCode: string
{
    case BE = 'be';
    case NL = 'nl';
    case FR = 'fr';

    public function flag(): string
    {
        return match($this) {
            self::BE => '/assets/img/flags/be.svg',
            self::NL => '/assets/img/flags/nl.svg',
            self::FR => '/assets/img/flags/fr.svg',
        };
    }

    public function label(): string
    {
        return __('country.' . $this->value);
    }

    public function labelShort(): string
    {
        return __('country.' . $this->value . '_short');
    }

    public static function options(): Collection
    {
        return collect(self::cases())->mapWithKeys(fn (self $country) => [$country->value => __('country.' . $country->value)]);
    }
}
