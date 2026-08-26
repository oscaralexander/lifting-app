<?php

namespace App\Enums;

enum InspectionType: string
{
    case TCVT = 'tcvt';
    case PERIODICAL = 'periodical';

    public function label(): string
    {
        return __('enums/inspection_type.'.$this->value);
    }

    /**
     * How long an inspection of this type remains valid, in months.
     */
    public function intervalMonths(): int
    {
        return match ($this) {
            self::TCVT => 24,
            self::PERIODICAL => 12,
        };
    }
}
