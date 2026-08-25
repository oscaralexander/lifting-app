<?php

namespace App\Enums;

enum InspectionStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case CAT_A_DEFICIENCIES = 'cat_a_deficiencies';
    case CAT_B_DEFICIENCIES = 'cat_b_deficiencies';

    public function label(): string
    {
        return __('enums/inspection_status.'.$this->value);
    }

    public static function options(): array
    {
        return array_column(array_map(fn (self $status) => [$status->value, $status->label()], self::cases()), 1, 0);
    }
}
