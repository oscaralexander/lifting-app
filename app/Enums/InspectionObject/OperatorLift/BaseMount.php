<?php

namespace App\Enums\InspectionObject\OperatorLift;

enum BaseMount: string
{
    case TOWER = 'tower';
    case RECOVERY_ANCHORS = 'recovery_anchors';
    case FLOOR_SLAB = 'floor_slab';
    case UNDERCARRIAGE = 'undercarriage';
    case OTHER = 'other';

    public function label(): string
    {
        return __('enums/inspection_object/operator_lift/base_mount.' . $this->value);
    }

    public static function options(): array
    {
        return array_column(array_map(fn(self $type) => [$type->value, $type->label()], self::cases()), 1, 0);
    }
}
