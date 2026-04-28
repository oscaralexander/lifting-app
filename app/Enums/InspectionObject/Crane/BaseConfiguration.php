<?php

namespace App\Enums\InspectionObject\Crane;

enum BaseConfiguration: string
{
    case CROSS_FRAME = 'cross_frame';
    case UNDERCARRIAGE = 'undercarriage';
    case RAIL_TRAVELLING = 'rail_travelling';
    case RECOVERABLE_ANCHORS = 'recoverable_anchors';
    case FOUNDATION_ANCHORS = 'foundation_anchors';

    public function label(): string
    {
        return __('enums/inspection_object/crane/base_configuration.'.$this->value);
    }

    public static function options(): array
    {
        return array_column(array_map(fn (self $type) => [$type->value, $type->label()], self::cases()), 1, 0);
    }
}
