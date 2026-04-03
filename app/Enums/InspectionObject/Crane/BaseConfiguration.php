<?php

namespace App\Enums\InspectionObject\Crane;

enum BaseConfiguration: string
{
    case RAIL_MOUNTED = 'rail_mounted';
    case RAIL_TRAVELLING = 'rail_travelling';
    case STATIONARY = 'stationary';
    case CAST_IN_BASE_FRAME = 'cast_in_base_frame';
    case RECOVERABLE_ANCHORS = 'recoverable_anchors';
    case FREESTANDING_CROSS_BASE = 'freestanding_cross_base';
    case FOUNDATION_ANCHORS = 'foundation_anchors';

    public function label(): string
    {
        return __('enums/inspection_object/crane/base_configuration.' . $this->value);
    }

    public static function options(): array
    {
        return array_column(array_map(fn(self $type) => [$type->value, $type->label()], self::cases()), 1, 0);
    }
}
