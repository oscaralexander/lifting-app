<?php

namespace App\Enums\InspectionObject\Crane;

enum BoomConfiguration: string
{
    case FIXED = 'fixed';
    case TROLLEY = 'trolley';
    case LUFFING = 'luffing';

    public function label(): string
    {
        return __('enums/inspection_object/crane/boom_configuration.' . $this->value);
    }

    public static function options(): array
    {
        return array_column(array_map(fn(self $type) => [$type->value, $type->label()], self::cases()), 1, 0);
    }
}
