<?php

namespace App\Enums\InspectionObject\Crane;

enum BallastConfiguration: string
{
    case COUNTERWEIGHT = 'counterweight';
    case CENTRAL = 'central';
    case SUPERLIFT = 'superlift';

    public function label(): string
    {
        return __('enums/inspection_object/ballast_configuration.' . $this->value);
    }

    public static function options(): array
    {
        return array_column(array_map(fn(self $type) => [$type->value, $type->label()], self::cases()), 1, 0);
    }
}
