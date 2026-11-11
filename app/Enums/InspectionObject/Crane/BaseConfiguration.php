<?php

namespace App\Enums\InspectionObject\Crane;

enum BaseConfiguration: string
{
    case CROSS_FRAME = 'cross_frame';
    case UNDERCARRIAGE = 'undercarriage';
    case RAIL_TRAVELLING = 'rail_travelling';
    case ANCHORS = 'anchors';
    case TYRES = 'tyres';
    case TRACKS = 'tracks';
    case OUTRIGGERS = 'outriggers';
    case TYRES_OUTRIGGERS = 'tyres_outriggers';
    case TYRES_DOZER_BLADE = 'tyres_dozer_blade';
    case OUTRIGGERS_DOZER_BLADE = 'outriggers_dozer_blade';

    public function label(): string
    {
        return __('enums/inspection_object/crane/base_configuration.'.$this->value);
    }

    public static function options(): array
    {
        return array_column(array_map(fn (self $type) => [$type->value, $type->label()], self::cases()), 1, 0);
    }
}
