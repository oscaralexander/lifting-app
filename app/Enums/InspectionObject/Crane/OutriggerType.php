<?php

namespace App\Enums\InspectionObject\Crane;

enum OutriggerType: string
{
    case OUTRIGGER = 'outrigger';
    case DOZER_BLADE = 'dozer_blade';

    public function label(): string
    {
        return __('enums/inspection_object/crane/outrigger_type.' . $this->value);
    }

    public static function options(): array
    {
        return array_column(array_map(fn(self $type) => [$type->value, $type->label()], self::cases()), 1, 0);
    }
}
