<?php

namespace App\Enums\InspectionObject\Crane;

enum Undercarriage: string
{
    case TYRES = 'tyres';
    case TRUCK = 'truck';
    case RAIL = 'rail';
    case ROAD = 'road';
    case TRACKS = 'tracks';
    case CONTAINER_BASE = 'container_base';

    public function label(): string
    {
        return __('enums/inspection_object/crane/undercarriage.' . $this->value);
    }

    public static function options(): array
    {
        return array_column(array_map(fn(self $type) => [$type->value, $type->label()], self::cases()), 1, 0);
    }
}
