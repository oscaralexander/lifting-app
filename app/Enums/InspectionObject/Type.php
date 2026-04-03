<?php

namespace App\Enums\InspectionObject;

enum Type: string
{
    case CRANE = 'crane';
    case OPERATOR_LIFT = 'operator_lift';

    public function label(): string
    {
        return __('enums/inspection_object/type.' . $this->value);
    }

    public static function options(): array
    {
        return array_column(array_map(fn(self $type) => [$type->value, $type->label()], self::cases()), 1, 0);
    }
}
