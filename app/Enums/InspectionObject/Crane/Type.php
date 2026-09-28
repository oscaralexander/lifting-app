<?php

namespace App\Enums\InspectionObject\Crane;

use App\Enums\TestMatrixType;

enum Type: string
{
    case MOBILE_CRANE = 'mobile_crane';
    case TOWER_CRANE = 'tower_crane';
    case MOBILE_TOWER_CRANE = 'mobile_tower_crane';
    case EARTHMOVER = 'earthmover';
    case LOADER_CRANE = 'loader_crane';
    case TELEHANDLER = 'telehandler';

    public function label(): string
    {
        return __('enums/inspection_object/crane/type.'.$this->value);
    }

    public function testMatrixType(): ?TestMatrixType
    {
        return match ($this) {
            self::TOWER_CRANE, self::MOBILE_TOWER_CRANE => TestMatrixType::TOWER_CRANE,
            default => null,
        };
    }

    public static function options(): array
    {
        return array_column(array_map(fn (self $type) => [$type->value, $type->label()], self::cases()), 1, 0);
    }
}
