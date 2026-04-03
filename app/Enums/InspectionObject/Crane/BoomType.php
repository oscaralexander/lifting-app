<?php

namespace App\Enums\InspectionObject\Crane;

enum BoomType: string
{
    case TELESCOPIC = 'telescopic';
    case LATTICE = 'lattice';
    case AUXILIARY = 'auxiliary';
    case ARTICULATED = 'articulated';
    case MONOBOOM = 'monoboom';
    case DIPPER_STICK = 'dipper_stick';
    case FLY_JIB = 'fly_jib';

    public function label(): string
    {
        return __('enums/inspection_object/crane/boom_type.' . $this->value);
    }

    public static function options(): array
    {
        return array_column(array_map(fn(self $type) => [$type->value, $type->label()], self::cases()), 1, 0);
    }
}
